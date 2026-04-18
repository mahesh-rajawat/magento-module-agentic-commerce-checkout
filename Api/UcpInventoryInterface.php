<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api;

interface UcpInventoryInterface
{
    /**
     * Query stock availability for one or more SKUs.
     *
     * @param string $sku   Single SKU or comma-separated list
     * @return mixed[]
     */
    public function query(string $sku): array;
}
