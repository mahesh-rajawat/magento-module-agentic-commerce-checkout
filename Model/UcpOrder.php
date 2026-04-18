<?php


declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\GuestCartManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use MSR\AgenticUcpCheckout\Api\UcpOrderInterface;
use MSR\AgenticUcpCheckout\Model\Cart\SessionManager;
use MSR\AgenticUcpCheckout\Model\UcpCart;
/**
 * Handles UCP agent order placement and tracking.
 */

class UcpOrder implements UcpOrderInterface
{
    public function __construct(
        private readonly UcpCart                      $ucpCart,
        private readonly SessionManager               $sessionManager,
        private readonly GuestCartManagementInterface $guestCartManagement,
        private readonly OrderRepositoryInterface     $orderRepository,
        private readonly OrderCollectionFactory       $orderCollectionFactory,
    ) {}

    public function place(string $paymentMethodCode, string $email): array
    {
        $quote = $this->ucpCart->getOrCreateQuote();

        if ($quote->getItemsCount() === 0) {
            return ['status' => 'error', 'message' => 'Cart is empty. Add items first.'];
        }

        if (!$quote->getShippingAddress()->getShippingMethod()) {
            return ['status' => 'error', 'message' => 'Shipping method not set. Call /ucp/checkout/shipping first.'];
        }

        // Set guest email
        $quote->setCustomerEmail($email)
              ->setCustomerIsGuest(true)
              ->setCheckoutMethod('guest'); // \Magento\Quote\Model\QuoteManagement::METHOD_GUEST

        // Set payment method
        $quote->getPayment()->setMethod($paymentMethodCode);

        try {
            $maskedId = $this->sessionManager->getMaskedCartId();
            $orderId  = $this->guestCartManagement->placeOrder($maskedId);
            $order    = $this->orderRepository->get($orderId);

            // Clear the agent's cart after successful order
            $this->sessionManager->clearCart();

            return [
                'status'           => 'ok',
                'message'          => 'Order placed successfully.',
                'order_id'         => (int)$orderId,
                'increment_id'     => $order->getIncrementId(),
                'grand_total'      => (float)$order->getGrandTotal(),
                'currency'         => $order->getOrderCurrencyCode(),
                'payment_method'   => $paymentMethodCode,
                'order_status'     => $order->getStatus(),
                'items_count'      => (int)$order->getTotalItemCount(),
            ];
        } catch (\Exception $e) {
            return [
                'status'  => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function track(string $orderId): array
    {
        // Support both numeric ID and increment ID (e.g. "000000001")
        try {
            if (is_numeric($orderId)) {
                $order = $this->orderRepository->get((int)$orderId);
            } else {
                $collection = $this->orderCollectionFactory->create()
                    ->addFieldToFilter('increment_id', $orderId)
                    ->setPageSize(1);
                $order = $collection->getFirstItem();
                if (!$order->getId()) {
                    return ['status' => 'error', 'message' => "Order '{$orderId}' not found."];
                }
            }
        } catch (NoSuchEntityException) {
            return ['status' => 'error', 'message' => "Order '{$orderId}' not found."];
        }

        $items = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $items[] = [
                'sku'      => $item->getSku(),
                'name'     => $item->getName(),
                'qty'      => (float)$item->getQtyOrdered(),
                'price'    => (float)$item->getPrice(),
                'row_total'=> (float)$item->getRowTotal(),
            ];
        }

        $tracks = [];
        foreach ($order->getTracksCollection() as $track) {
            $tracks[] = [
                'carrier'       => $track->getCarrierCode(),
                'title'         => $track->getTitle(),
                'tracking_number'=> $track->getTrackNumber(),
            ];
        }

        return [
            'status'         => 'ok',
            'order_id'       => (int)$order->getId(),
            'increment_id'   => $order->getIncrementId(),
            'order_status'   => $order->getStatus(),
            'order_state'    => $order->getState(),
            'grand_total'    => (float)$order->getGrandTotal(),
            'currency'       => $order->getOrderCurrencyCode(),
            'created_at'     => $order->getCreatedAt(),
            'items'          => $items,
            'tracking'       => $tracks,
            'shipping_method'=> $order->getShippingDescription(),
        ];
    }
}
