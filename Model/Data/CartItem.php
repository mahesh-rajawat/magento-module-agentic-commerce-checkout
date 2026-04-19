<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model\Data;

use Magento\Framework\DataObject;
use MSR\AgenticUcpCheckout\Api\Data\CartItemInterface;

/**
 * UCP cart item data model.
 */
class CartItem extends DataObject implements CartItemInterface
{
    /**
     * Get the cart item ID.
     *
     * @return int
     */
    public function getItemId(): int
    {
        return (int)$this->getData(self::ITEM_ID);
    }

    /**
     * Set the cart item ID.
     *
     * @param int $itemId
     * @return static
     */
    public function setItemId(int $itemId): static
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }

    /**
     * Get the product SKU.
     *
     * @return string
     */
    public function getSku(): string
    {
        return (string)$this->getData(self::SKU);
    }

    /**
     * Set the product SKU.
     *
     * @param string $sku
     * @return static
     */
    public function setSku(string $sku): static
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    /**
     * Set the product name.
     *
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get the item quantity.
     *
     * @return float
     */
    public function getQty(): float
    {
        return (float)$this->getData(self::QTY);
    }

    /**
     * Set the item quantity.
     *
     * @param float $qty
     * @return static
     */
    public function setQty(float $qty): static
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * Get the unit price.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return (float)$this->getData(self::PRICE);
    }

    /**
     * Set the unit price.
     *
     * @param float $price
     * @return static
     */
    public function setPrice(float $price): static
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Get the row total.
     *
     * @return float
     */
    public function getRowTotal(): float
    {
        return (float)$this->getData(self::ROW_TOTAL);
    }

    /**
     * Set the row total.
     *
     * @param float $rowTotal
     * @return static
     */
    public function setRowTotal(float $rowTotal): static
    {
        return $this->setData(self::ROW_TOTAL, $rowTotal);
    }
}
