<?php
declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api;

use MSR\AgenticUcpCheckout\Api\Data\CartItemInterface;

interface UcpCartInterface
{
    /**
     * Return the current agent cart summary.
     *
     * @return mixed[]
     */
    public function view(): array;

    /**
     * Add a product to the cart.
     *
     * @param string $sku
     * @param int    $qty
     * @return mixed[]
     */
    public function addItem(string $sku, int $qty = 1): array;

    /**
     * Remove a specific item from the cart by quote item ID.
     *
     * @param int $itemId
     * @return mixed[]
     */
    public function removeItem(int $itemId): array;

    /**
     * Clear all items from the cart.
     *
     * @return mixed[]
     */
    public function clear(): array;
}
