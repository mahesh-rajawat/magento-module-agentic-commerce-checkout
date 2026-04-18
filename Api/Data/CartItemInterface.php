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
     * @return int
     */
    public function getItemId(): int;

    /**
     * @param int $itemId
     * @return $this
     */
    public function setItemId(int $itemId): static;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): static;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static;

    /**
     * @return float
     */
    public function getQty(): float;

    /**
     * @param float $qty
     * @return $this
     */
    public function setQty(float $qty): static;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): static;

    /**
     * @return float
     */
    public function getRowTotal(): float;

    /**
     * @param float $rowTotal
     * @return $this
     */
    public function setRowTotal(float $rowTotal): static;
}
