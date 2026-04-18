<?php


declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model\Data;

use Magento\Framework\DataObject;
use MSR\AgenticUcpCheckout\Api\Data\OrderResultInterface;
/**
 * UCP order placement result data model.
 */

class OrderResult extends DataObject implements OrderResultInterface
{
    public function getStatus(): string
    {
        return (string)$this->getData(self::STATUS);
    }

    public function setStatus(string $status): static
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getMessage(): string
    {
        return (string)$this->getData(self::MESSAGE);
    }

    public function setMessage(string $message): static
    {
        return $this->setData(self::MESSAGE, $message);
    }

    public function getOrderId(): int
    {
        return (int)$this->getData(self::ORDER_ID);
    }

    public function setOrderId(int $orderId): static
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    public function getIncrementId(): string
    {
        return (string)$this->getData(self::INCREMENT_ID);
    }

    public function setIncrementId(string $incrementId): static
    {
        return $this->setData(self::INCREMENT_ID, $incrementId);
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

    public function getPaymentMethod(): string
    {
        return (string)$this->getData(self::PAYMENT_METHOD);
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        return $this->setData(self::PAYMENT_METHOD, $paymentMethod);
    }

    public function getOrderStatus(): string
    {
        return (string)$this->getData(self::ORDER_STATUS);
    }

    public function setOrderStatus(string $orderStatus): static
    {
        return $this->setData(self::ORDER_STATUS, $orderStatus);
    }

    public function getItemsCount(): int
    {
        return (int)$this->getData(self::ITEMS_COUNT);
    }

    public function setItemsCount(int $itemsCount): static
    {
        return $this->setData(self::ITEMS_COUNT, $itemsCount);
    }
}
