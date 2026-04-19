<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api\Data;

/**
 * UCP cart summary data interface.
 */
interface CartSummaryInterface
{
    public const QUOTE_ID    = 'quote_id';
    public const ITEMS_COUNT = 'items_count';
    public const ITEMS       = 'items';
    public const SUBTOTAL    = 'subtotal';
    public const GRAND_TOTAL = 'grand_total';
    public const CURRENCY    = 'currency';

    /**
     * Get the quote ID.
     *
     * @return int
     */
    public function getQuoteId(): int;

    /**
     * Set the quote ID.
     *
     * @param int $quoteId
     * @return $this
     */
    public function setQuoteId(int $quoteId): static;

    /**
     * Get the number of items in the cart.
     *
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * Set the number of items in the cart.
     *
     * @param int $itemsCount
     * @return $this
     */
    public function setItemsCount(int $itemsCount): static;

    /**
     * Get the cart items.
     *
     * @return \MSR\AgenticUcpCheckout\Api\Data\CartItemInterface[]
     */
    public function getItems(): array;

    /**
     * Set the cart items.
     *
     * @param \MSR\AgenticUcpCheckout\Api\Data\CartItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items): static;

    /**
     * Get the cart subtotal.
     *
     * @return float
     */
    public function getSubtotal(): float;

    /**
     * Set the cart subtotal.
     *
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotal(float $subtotal): static;

    /**
     * Get the cart grand total.
     *
     * @return float
     */
    public function getGrandTotal(): float;

    /**
     * Set the cart grand total.
     *
     * @param float $grandTotal
     * @return $this
     */
    public function setGrandTotal(float $grandTotal): static;

    /**
     * Get the currency code.
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Set the currency code.
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): static;
}
