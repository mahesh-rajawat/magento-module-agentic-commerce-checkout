<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\GuestCartManagementInterface;
use Magento\Quote\Api\GuestCartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Magento\Store\Model\StoreManagerInterface;
use MSR\AgenticUcpCheckout\Api\UcpCartInterface;
use MSR\AgenticUcpCheckout\Model\Cart\SessionManager;

/**
 * Manages the UCP agent guest cart via Magento quote API.
 */
class UcpCart implements UcpCartInterface
{
    /**
     * @param GuestCartManagementInterface $guestCartManagement
     * @param GuestCartRepositoryInterface $guestCartRepository
     * @param CartRepositoryInterface $cartRepository
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param SessionManager $sessionManager
     */
    public function __construct(
        private readonly GuestCartManagementInterface $guestCartManagement,
        private readonly GuestCartRepositoryInterface $guestCartRepository,
        private readonly CartRepositoryInterface      $cartRepository,
        private readonly ProductRepositoryInterface   $productRepository,
        private readonly StoreManagerInterface        $storeManager,
        private readonly SessionManager               $sessionManager,
    ) {
    }

    /**
     * Return the current cart contents.
     *
     * @return array
     */
    public function view(): array
    {
        $quote = $this->getOrCreateQuote();
        return $this->formatCart($quote);
    }

    /**
     * Add a product to the cart by SKU.
     *
     * @param string $sku
     * @param int $qty
     * @return array
     */
    public function addItem(string $sku, int $qty = 1): array
    {
        try {
            $product = $this->productRepository->get($sku);
        } catch (NoSuchEntityException) {
            return ['status' => 'error', 'message' => "Product '{$sku}' not found."];
        }

        if (!$product->isSaleable()) {
            return ['status' => 'error', 'message' => "Product '{$sku}' is not available for sale."];
        }

        $quote = $this->getOrCreateQuote();

        try {
            $quote->addProduct($product, $qty);
            $this->cartRepository->save($quote);
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

        return [
            'status'  => 'ok',
            'message' => "Added {$qty}x '{$product->getName()}' to cart.",
            'cart'    => $this->formatCart($quote),
        ];
    }

    /**
     * Remove an item from the cart by item ID.
     *
     * @param int $itemId
     * @return array
     */
    public function removeItem(int $itemId): array
    {
        $quote = $this->getOrCreateQuote();
        $item  = $quote->getItemById($itemId);

        if (!$item) {
            return ['status' => 'error', 'message' => "Cart item {$itemId} not found."];
        }

        $name = $item->getName();
        $quote->removeItem($itemId);
        $this->cartRepository->save($quote);

        return [
            'status'  => 'ok',
            'message' => "Removed '{$name}' from cart.",
            'cart'    => $this->formatCart($quote),
        ];
    }

    /**
     * Remove all items from the cart.
     *
     * @return array
     */
    public function clear(): array
    {
        $quote = $this->getOrCreateQuote();
        foreach ($quote->getAllItems() as $item) {
            $quote->removeItem($item->getId());
        }
        $this->cartRepository->save($quote);

        return [
            'status'  => 'ok',
            'message' => 'Cart cleared.',
            'cart'    => $this->formatCart($quote),
        ];
    }

    /**
     * Get the existing agent cart quote or create a new guest cart.
     *
     * @return Quote
     */
    public function getOrCreateQuote(): Quote
    {
        $maskedId = $this->sessionManager->getMaskedCartId();

        if ($maskedId) {
            try {
                return $this->guestCartRepository->get($maskedId);
            } catch (NoSuchEntityException) { // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
                // Cart expired — create a new one
            }
        }

        // Create a new guest cart
        $newMaskedId = $this->guestCartManagement->createEmptyCart();
        $this->sessionManager->setMaskedCartId($newMaskedId);

        $quote = $this->guestCartRepository->get($newMaskedId);
        $quote->setStoreId($this->storeManager->getStore()->getId());
        $this->cartRepository->save($quote);

        return $quote;
    }

    /**
     * Format a quote as a cart summary array.
     *
     * @param Quote $quote
     * @return array
     */
    public function formatCart(Quote $quote): array
    {
        $items = [];
        foreach ($quote->getAllVisibleItems() as $item) {
            $items[] = [
                'item_id'  => (int)$item->getId(),
                'sku'      => $item->getSku(),
                'name'     => $item->getName(),
                'qty'      => (float)$item->getQty(),
                'price'    => (float)$item->getPrice(),
                'row_total'=> (float)$item->getRowTotal(),
            ];
        }

        return [
            'quote_id'    => $quote->getId(),
            'items_count' => count($items),
            'items'       => $items,
            'subtotal'    => (float)$quote->getSubtotal(),
            'grand_total' => (float)$quote->getGrandTotal(),
            'currency'    => $quote->getQuoteCurrencyCode(),
        ];
    }
}
