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
    public function getQuoteId(): int
    {
        return (int)$this->getData(self::QUOTE_ID);
    }

    public function setQuoteId(int $quoteId): static
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    public function getItemsCount(): int
    {
        return (int)$this->getData(self::ITEMS_COUNT);
    }

    public function setItemsCount(int $itemsCount): static
    {
        return $this->setData(self::ITEMS_COUNT, $itemsCount);
    }

    public function getItems(): array
    {
        return (array)($this->getData(self::ITEMS) ?? []);
    }

    public function setItems(array $items): static
    {
        return $this->setData(self::ITEMS, $items);
    }

    public function getSubtotal(): float
    {
        return (float)$this->getData(self::SUBTOTAL);
    }

    public function setSubtotal(float $subtotal): static
    {
        return $this->setData(self::SUBTOTAL, $subtotal);
    }

    public function getGrandTotal(): float
    {
        return (float)$this->getData(self::GRAND_TOTAL);
    }

    public function setGrandTotal(float $grandTotal): static
    {
        return $this->setData(self::GRAND_TOTAL, $grandTotal);
    }

    public function getCurrency(): string
    {
        return (string)$this->getData(self::CURRENCY);
    }

    public function setCurrency(string $currency): static
    {
        return $this->setData(self::CURRENCY, $currency);
    }
}
