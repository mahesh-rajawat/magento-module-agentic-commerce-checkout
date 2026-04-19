<?php
declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api;

interface UcpOrderInterface
{
    /**
     * Place the order from the active cart.
     *
     * Requires X-UCP-Human-Confirmation header (policy middleware checks this).
     *
     * @param string $paymentMethodCode  e.g. "checkmo" or "stripe"
     * @param string $email              Guest email address
     * @return mixed[]
     */
    public function place(string $paymentMethodCode, string $email): array;

    /**
     * Track an existing order by increment ID.
     *
     * @param string $orderId  Order increment ID e.g. "000000001"
     * @return mixed[]
     */
    public function track(string $orderId): array;
}
