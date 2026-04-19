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
    /**
     * Get the result status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return (string)$this->getData(self::STATUS);
    }

    /**
     * Set the result status.
     *
     * @param string $status
     * @return static
     */
    public function setStatus(string $status): static
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get the result message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return (string)$this->getData(self::MESSAGE);
    }

    /**
     * Set the result message.
     *
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): static
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * Get the order ID.
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return (int)$this->getData(self::ORDER_ID);
    }

    /**
     * Set the order ID.
     *
     * @param int $orderId
     * @return static
     */
    public function setOrderId(int $orderId): static
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Get the order increment ID.
     *
     * @return string
     */
    public function getIncrementId(): string
    {
        return (string)$this->getData(self::INCREMENT_ID);
    }

    /**
     * Set the order increment ID.
     *
     * @param string $incrementId
     * @return static
     */
    public function setIncrementId(string $incrementId): static
    {
        return $this->setData(self::INCREMENT_ID, $incrementId);
    }

    /**
     * Get the order grand total.
     *
     * @return float
     */
    public function getGrandTotal(): float
    {
        return (float)$this->getData(self::GRAND_TOTAL);
    }

    /**
     * Set the order grand total.
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

    /**
     * Get the payment method code.
     *
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return (string)$this->getData(self::PAYMENT_METHOD);
    }

    /**
     * Set the payment method code.
     *
     * @param string $paymentMethod
     * @return static
     */
    public function setPaymentMethod(string $paymentMethod): static
    {
        return $this->setData(self::PAYMENT_METHOD, $paymentMethod);
    }

    /**
     * Get the order status.
     *
     * @return string
     */
    public function getOrderStatus(): string
    {
        return (string)$this->getData(self::ORDER_STATUS);
    }

    /**
     * Set the order status.
     *
     * @param string $orderStatus
     * @return static
     */
    public function setOrderStatus(string $orderStatus): static
    {
        return $this->setData(self::ORDER_STATUS, $orderStatus);
    }

    /**
     * Get the items count.
     *
     * @return int
     */
    public function getItemsCount(): int
    {
        return (int)$this->getData(self::ITEMS_COUNT);
    }

    /**
     * Set the items count.
     *
     * @param int $itemsCount
     * @return static
     */
    public function setItemsCount(int $itemsCount): static
    {
        return $this->setData(self::ITEMS_COUNT, $itemsCount);
    }
}
