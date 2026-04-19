<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api\Data;

/**
 * UCP cart item data interface.
 */
interface CartItemInterface
{
    public const ITEM_ID   = 'item_id';
    public const SKU       = 'sku';
    public const NAME      = 'name';
    public const QTY       = 'qty';
    public const PRICE     = 'price';
    public const ROW_TOTAL = 'row_total';

    /**
     * Get the cart item ID.
     *
     * @return int
     */
    public function getItemId(): int;

    /**
     * Set the cart item ID.
     *
     * @param int $itemId
     * @return $this
     */
    public function setItemId(int $itemId): static;

    /**
     * Get the product SKU.
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set the product SKU.
     *
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): static;

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the product name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static;

    /**
     * Get the item quantity.
     *
     * @return float
     */
    public function getQty(): float;

    /**
     * Set the item quantity.
     *
     * @param float $qty
     * @return $this
     */
    public function setQty(float $qty): static;

    /**
     * Get the unit price.
     *
     * @return float
     */
    public function getPrice(): float;

    /**
     * Set the unit price.
     *
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): static;

    /**
     * Get the row total.
     *
     * @return float
     */
    public function getRowTotal(): float;

    /**
     * Set the row total.
     *
     * @param float $rowTotal
     * @return $this
     */
    public function setRowTotal(float $rowTotal): static;
}
