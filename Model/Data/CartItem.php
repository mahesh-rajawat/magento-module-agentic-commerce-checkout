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
    public function getItemId(): int
    {
        return (int)$this->getData(self::ITEM_ID);
    }

    public function setItemId(int $itemId): static
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }

    public function getSku(): string
    {
        return (string)$this->getData(self::SKU);
    }

    public function setSku(string $sku): static
    {
        return $this->setData(self::SKU, $sku);
    }

    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    public function setName(string $name): static
    {
        return $this->setData(self::NAME, $name);
    }

    public function getQty(): float
    {
        return (float)$this->getData(self::QTY);
    }

    public function setQty(float $qty): static
    {
        return $this->setData(self::QTY, $qty);
    }

    public function getPrice(): float
    {
        return (float)$this->getData(self::PRICE);
    }

    public function setPrice(float $price): static
    {
        return $this->setData(self::PRICE, $price);
    }

    public function getRowTotal(): float
    {
        return (float)$this->getData(self::ROW_TOTAL);
    }

    public function setRowTotal(float $rowTotal): static
    {
        return $this->setData(self::ROW_TOTAL, $rowTotal);
    }
}
