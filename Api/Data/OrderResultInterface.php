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
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): static;

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId(int $orderId): static;

    /**
     * @return string
     */
    public function getIncrementId(): string;

    /**
     * @param string $incrementId
     * @return $this
     */
    public function setIncrementId(string $incrementId): static;

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

    /**
     * @return string
     */
    public function getPaymentMethod(): string;

    /**
     * @param string $paymentMethod
     * @return $this
     */
    public function setPaymentMethod(string $paymentMethod): static;

    /**
     * @return string
     */
    public function getOrderStatus(): string;

    /**
     * @param string $orderStatus
     * @return $this
     */
    public function setOrderStatus(string $orderStatus): static;

    /**
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * @param int $itemsCount
     * @return $this
     */
    public function setItemsCount(int $itemsCount): static;
}
