<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use MSR\AgenticUcpCheckout\Api\UcpInventoryInterface;

/**
 * Queries stock availability for one or more SKUs.
 */
class UcpInventory implements UcpInventoryInterface
{
    /**
     * @param StockRegistryInterface $stockRegistry
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        private readonly StockRegistryInterface    $stockRegistry,
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    /**
     * Query stock levels for a SKU or comma-separated list of SKUs.
     *
     * @param string $sku
     * @return array
     */
    public function query(string $sku): array
    {
        // Support comma-separated SKU list
        $skus    = array_map('trim', explode(',', $sku));
        $results = [];

        foreach ($skus as $s) {
            if (empty($s)) {
                continue;
            }

            try {
                $product    = $this->productRepository->get($s);
                $stockItem  = $this->stockRegistry->getStockItem($product->getId());

                $results[] = [
                    'sku'          => $s,
                    'name'         => $product->getName(),
                    'in_stock'     => (bool)$stockItem->getIsInStock(),
                    'qty'          => (float)$stockItem->getQty(),
                    'min_qty'      => (float)$stockItem->getMinQty(),
                    'is_saleable'  => (bool)$product->isSaleable(),
                    'manage_stock' => (bool)$stockItem->getManageStock(),
                ];
            } catch (NoSuchEntityException) {
                $results[] = [
                    'sku'     => $s,
                    'error'   => "Product '{$s}' not found.",
                ];
            }
        }

        return [
            'status'  => 'ok',
            'results' => $results,
        ];
    }
}
