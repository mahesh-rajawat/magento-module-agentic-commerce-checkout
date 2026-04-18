<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api\Data;

interface CartSummaryInterface
{
    public const QUOTE_ID    = 'quote_id';
    public const ITEMS_COUNT = 'items_count';
    public const ITEMS       = 'items';
    public const SUBTOTAL    = 'subtotal';
    public const GRAND_TOTAL = 'grand_total';
    public const CURRENCY    = 'currency';

    /**
     * @return int
     */
    public function getQuoteId(): int;

    /**
     * @param int $quoteId
     * @return $this
     */
    public function setQuoteId(int $quoteId): static;

    /**
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * @param int $itemsCount
     * @return $this
     */
    public function setItemsCount(int $itemsCount): static;

    /**
     * @return \MSR\AgenticUcpCheckout\Api\Data\CartItemInterface[]
     */
    public function getItems(): array;

    /**
     * @param \MSR\AgenticUcpCheckout\Api\Data\CartItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items): static;

    /**
     * @return float
     */
    public function getSubtotal(): float;

    /**
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotal(float $subtotal): static;

    /**
     * @return float
     */
    public function getGrandTotal(): float;

    /**
     * @param float $grandTotal
     * @return $this
     */
    public function setGrandTotal(float $grandTotal): static;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): static;
}
