<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model\Data;

use Magento\Framework\DataObject;
use MSR\AgenticUcpCheckout\Api\Data\CartSummaryInterface;

/**
 * UCP cart summary data model.
 */
class CartSummary extends DataObject implements CartSummaryInterface
{
    /**
     * Get the quote ID.
     *
     * @return int
     */
    public function getQuoteId(): int
    {
        return (int)$this->getData(self::QUOTE_ID);
    }

    /**
     * Set the quote ID.
     *
     * @param int $quoteId
     * @return static
     */
    public function setQuoteId(int $quoteId): static
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * Get the number of items in the cart.
     *
     * @return int
     */
    public function getItemsCount(): int
    {
        return (int)$this->getData(self::ITEMS_COUNT);
    }

    /**
     * Set the number of items in the cart.
     *
     * @param int $itemsCount
     * @return static
     */
    public function setItemsCount(int $itemsCount): static
    {
        return $this->setData(self::ITEMS_COUNT, $itemsCount);
    }

    /**
     * Get the cart items.
     *
     * @return array
     */
    public function getItems(): array
    {
        return (array)($this->getData(self::ITEMS) ?? []);
    }

    /**
     * Set the cart items.
     *
     * @param array $items
     * @return static
     */
    public function setItems(array $items): static
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * Get the cart subtotal.
     *
     * @return float
     */
    public function getSubtotal(): float
    {
        return (float)$this->getData(self::SUBTOTAL);
    }

    /**
     * Set the cart subtotal.
     *
     * @param float $subtotal
     * @return static
     */
    public function setSubtotal(float $subtotal): static
    {
        return $this->setData(self::SUBTOTAL, $subtotal);
    }

    /**
     * Get the cart grand total.
     *
     * @return float
     */
    public function getGrandTotal(): float
    {
        return (float)$this->getData(self::GRAND_TOTAL);
    }

    /**
     * Set the cart grand total.
     *
     * @param float $grandTotal
     * @return static
     */
    public function setGrandTotal(float $grandTotal): static
    {
        return $this->setData(self::GRAND_TOTAL, $grandTotal);
    }

    /**
     * Get the currency code.
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return (string)$this->getData(self::CURRENCY);
    }

    /**
     * Set the currency code.
     *
     * @param string $currency
     * @return static
     */
    public function setCurrency(string $currency): static
    {
        return $this->setData(self::CURRENCY, $currency);
    }
}
