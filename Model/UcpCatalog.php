<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Store\Model\StoreManagerInterface;
use MSR\AgenticUcpCheckout\Api\UcpCatalogInterface;

/**
 * Provides catalog browsing and search for UCP agents.
 */
class UcpCatalog implements UcpCatalogInterface
{
    /**
     * @param CollectionFactory $collectionFactory
     * @param StockRegistryInterface $stockRegistry
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly CollectionFactory      $collectionFactory,
        private readonly StockRegistryInterface $stockRegistry,
        private readonly StoreManagerInterface  $storeManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function browse(int $page = 1, int $pageSize = 20): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect(['name', 'price', 'short_description', 'image'])
                   ->addAttributeToFilter('status', ProductStatus::STATUS_ENABLED)
                   ->addAttributeToFilter('visibility', [
                       'in' => [
                           Visibility::VISIBILITY_IN_CATALOG,
                           Visibility::VISIBILITY_BOTH,
                       ]
                   ])
                   ->setPageSize($pageSize)
                   ->setCurPage($page)
                   ->addFinalPrice();

        $items = [];
        foreach ($collection as $product) {
            $items[] = $this->formatProduct($product);
        }

        return [
            'status'      => 'ok',
            'page'        => $page,
            'page_size'   => $pageSize,
            'total_count' => $collection->getSize(),
            'items'       => $items,
        ];
    }

    /**
     * @inheritDoc
     */
    public function search(string $q, int $page = 1, int $pageSize = 20): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect(['name', 'price', 'short_description', 'image'])
                   ->addAttributeToFilter('status', ProductStatus::STATUS_ENABLED)
                   ->addAttributeToFilter('visibility', [
                       'in' => [
                           Visibility::VISIBILITY_IN_CATALOG,
                           Visibility::VISIBILITY_BOTH,
                       ]
                   ])
                   ->addAttributeToFilter([
                       ['attribute' => 'name',              'like' => "%{$q}%"],
                       ['attribute' => 'short_description', 'like' => "%{$q}%"],
                       ['attribute' => 'sku',               'like' => "%{$q}%"],
                   ])
                   ->setPageSize($pageSize)
                   ->setCurPage($page)
                   ->addFinalPrice();

        $items = [];
        foreach ($collection as $product) {
            $items[] = $this->formatProduct($product);
        }

        return [
            'status'      => 'ok',
            'query'       => $q,
            'page'        => $page,
            'page_size'   => $pageSize,
            'total_count' => $collection->getSize(),
            'items'       => $items,
        ];
    }

    /**
     * Format a product as an array for the agent response.
     *
     * @param Product $product
     * @return array
     */
    private function formatProduct(Product $product): array
    {
        $stock     = $this->stockRegistry->getStockItem($product->getId());
        $currency  = $this->storeManager->getStore()->getCurrentCurrencyCode();

        // Regular price (before any discounts)
        $regularPrice = (float)$product->getPrice();

        // Final price for qty=1 — includes special price if active
        $finalPrice = (float)$product->getFinalPrice(1);

        // Special price (if set and currently active)
        $specialPrice    = null;
        $specialFrom     = $product->getSpecialFromDate();
        $specialTo       = $product->getSpecialToDate();
        $rawSpecialPrice = $product->getSpecialPrice();

        if ($rawSpecialPrice) {
            $now    = new \DateTime();
            $fromOk = !$specialFrom || new \DateTime($specialFrom) <= $now;
            $toOk   = !$specialTo || new \DateTime($specialTo) >= $now;
            if ($fromOk && $toOk) {
                $specialPrice = (float)$rawSpecialPrice;
            }
        }

        // Tier / group prices (price breaks by quantity)
        $tierPrices = [];
        foreach ($product->getTierPrices() ?? [] as $tier) {
            $tierPrices[] = [
                'qty'              => (float)$tier->getQty(),
                'price'            => (float)$tier->getValue(),
                'customer_group'   => $tier->getCustomerGroupId() === '32000'
                    ? 'all groups'
                    : 'group ' . $tier->getCustomerGroupId(),
            ];
        }

        // Sort tier prices by qty ascending
        usort($tierPrices, fn($a, $b) => $a['qty'] <=> $b['qty']);

        // Build a human-readable price summary for the agent
        $priceSummary = $currency . ' ' . number_format($finalPrice, 2);
        if ($specialPrice && $specialPrice < $regularPrice) {
            $priceSummary .= ' (on sale, was ' . $currency . ' '
                . number_format($regularPrice, 2) . ')';
        }
        if (!empty($tierPrices)) {
            $breaks = array_map(
                fn($t) => 'buy ' . (int)$t['qty'] . '+ for '
                    . $currency . ' ' . number_format($t['price'], 2),
                $tierPrices
            );
            $priceSummary .= ' — ' . implode(', ', $breaks);
        }

        return [
            'id'                => (int)$product->getId(),
            'sku'               => $product->getSku(),
            'name'              => $product->getName(),
            'currency'          => $currency,

            // All price tiers — agent uses these to answer price questions accurately
            'price'             => $finalPrice,          // what you pay for qty=1 right now
            'regular_price'     => $regularPrice,         // original price without discounts
            'special_price'     => $specialPrice,         // sale price (null if not on sale)
            'tier_prices'       => $tierPrices,           // quantity break prices

            // Plain English summary for the agent to use directly in conversation
            'price_summary'     => $priceSummary,

            'in_stock'          => (bool)$stock->getIsInStock(),
            'qty'               => (float)$stock->getQty(),
            'short_description' => strip_tags((string)$product->getShortDescription()),
            'url'               => $product->getProductUrl(),
        ];
    }
}
