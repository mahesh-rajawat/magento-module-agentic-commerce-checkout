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
     * @param Product $product
     * @return array
     */
    private function formatProduct(Product $product): array
    {
        $stock = $this->stockRegistry->getStockItem($product->getId());
        return [
            'id'                => (int)$product->getId(),
            'sku'               => $product->getSku(),
            'name'              => $product->getName(),
            'price'             => (float)$product->getFinalPrice(),
            'currency'          => $this->storeManager->getStore()->getCurrentCurrencyCode(),
            'in_stock'          => (bool)$stock->getIsInStock(),
            'qty'               => (float)$stock->getQty(),
            'short_description' => strip_tags((string)$product->getShortDescription()),
            'url'               => $product->getProductUrl(),
        ];
    }
}
