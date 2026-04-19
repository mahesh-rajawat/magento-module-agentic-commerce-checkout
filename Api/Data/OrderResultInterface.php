<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api\Data;

/**
 * UCP order placement result data interface.
 */
interface OrderResultInterface
{
    public const STATUS         = 'status';
    public const MESSAGE        = 'message';
    public const ORDER_ID       = 'order_id';
    public const INCREMENT_ID   = 'increment_id';
    public const GRAND_TOTAL    = 'grand_total';
    public const CURRENCY       = 'currency';
    public const PAYMENT_METHOD = 'payment_method';
    public const ORDER_STATUS   = 'order_status';
    public const ITEMS_COUNT    = 'items_count';

    /**
     * Get the result status.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set the result status.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static;

    /**
     * Get the result message.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Set the result message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): static;

    /**
     * Get the order ID.
     *
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Set the order ID.
     *
     * @param int $orderId
     * @return $this
     */
    public function setOrderId(int $orderId): static;

    /**
     * Get the order increment ID.
     *
     * @return string
     */
    public function getIncrementId(): string;

    /**
     * Set the order increment ID.
     *
     * @param string $incrementId
     * @return $this
     */
    public function setIncrementId(string $incrementId): static;

    /**
     * Get the order grand total.
     *
     * @return float
     */
    public function getGrandTotal(): float;

    /**
     * Set the order grand total.
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

    /**
     * Get the payment method code.
     *
     * @return string
     */
    public function getPaymentMethod(): string;

    /**
     * Set the payment method code.
     *
     * @param string $paymentMethod
     * @return $this
     */
    public function setPaymentMethod(string $paymentMethod): static;

    /**
     * Get the order status.
     *
     * @return string
     */
    public function getOrderStatus(): string;

    /**
     * Set the order status.
     *
     * @param string $orderStatus
     * @return $this
     */
    public function setOrderStatus(string $orderStatus): static;

    /**
     * Get the items count.
     *
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * Set the items count.
     *
     * @param int $itemsCount
     * @return $this
     */
    public function setItemsCount(int $itemsCount): static;
}
