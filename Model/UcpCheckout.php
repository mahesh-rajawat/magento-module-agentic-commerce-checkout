<?php


declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Magento\Quote\Model\Quote;
use MSR\AgenticUcpCheckout\Api\UcpCheckoutInterface;
/**
 * Manages UCP agent checkout flow: shipping, payment, and totals.
 */

class UcpCheckout implements UcpCheckoutInterface
{
    public function __construct(
        private readonly UcpCart                           $ucpCart,
        private readonly CartRepositoryInterface           $cartRepository,
        private readonly ShippingMethodManagementInterface $shippingMethodManagement,
        private readonly PaymentMethodManagementInterface  $paymentMethodManagement,
    ) {
    }

    public function setShipping(
        string $firstname,
        string $lastname,
        string $street,
        string $city,
        string $regionCode,
        string $postcode,
        string $countryId,
        string $telephone,
        string $shippingMethodCode
    ): array {
        $quote = $this->ucpCart->getOrCreateQuote();

        if ($quote->getItemsCount() === 0) {
            return ['status' => 'error', 'message' => 'Cart is empty. Add items before setting shipping.'];
        }

        // Build address
        $address = $quote->getShippingAddress();
        $address->setFirstname($firstname)
                ->setLastname($lastname)
                ->setStreet([$street])
                ->setCity($city)
                ->setRegionCode($regionCode)
                ->setPostcode($postcode)
                ->setCountryId($countryId)
                ->setTelephone($telephone)
                ->setEmail($quote->getCustomerEmail() ?? 'agent@ucp.local');

        // Set same address for billing
        $quote->getBillingAddress()->addData($address->getData());

        // Parse shipping method (format: "carrier_method") — validated by Magento's rate collection
        $address->setShippingMethod($shippingMethodCode)
                ->setCollectShippingRates(true)
                ->collectShippingRates();

        $quote->setTotalsCollectedFlag(false)->collectTotals();
        $this->cartRepository->save($quote);

        return [
            'status'          => 'ok',
            'message'         => 'Shipping address and method set.',
            'shipping_method' => $shippingMethodCode,
            'totals'          => $this->formatTotals($quote),
        ];
    }

    public function getShippingMethods(): array
    {
        $quote = $this->ucpCart->getOrCreateQuote();

        if ($quote->getItemsCount() === 0) {
            return ['status' => 'error', 'message' => 'Cart is empty.'];
        }

        $address = $quote->getShippingAddress();
        $address->setCollectShippingRates(true)->collectShippingRates();

        $methods = [];
        foreach ($address->getGroupedAllShippingRates() as $carrier) {
            foreach ($carrier as $rate) {
                $methods[] = [
                    'code'         => $rate->getCode(),
                    'carrier'      => $rate->getCarrierTitle(),
                    'method'       => $rate->getMethodTitle(),
                    'price'        => (float)$rate->getPrice(),
                    'error'        => $rate->getErrorMessage(),
                ];
            }
        }

        return [
            'status'  => 'ok',
            'methods' => $methods,
        ];
    }

    public function getPaymentMethods(): array
    {
        $quote   = $this->ucpCart->getOrCreateQuote();
        $methods = $this->paymentMethodManagement->getList($quote->getId());

        $result = [];
        foreach ($methods as $method) {
            $result[] = [
                'code'  => $method->getCode(),
                'title' => $method->getTitle(),
            ];
        }

        return [
            'status'  => 'ok',
            'methods' => $result,
        ];
    }

    public function getTotals(): array
    {
        $quote = $this->ucpCart->getOrCreateQuote();

        if ($quote->getItemsCount() === 0) {
            return ['status' => 'error', 'message' => 'Cart is empty.'];
        }

        $quote->setTotalsCollectedFlag(false)->collectTotals();

        return [
            'status'  => 'ok',
            'totals'  => $this->formatTotals($quote),
        ];
    }

    private function formatTotals(Quote $quote): array
    {
        return [
            'subtotal'          => (float)$quote->getSubtotal(),
            'shipping'          => (float)$quote->getShippingAddress()->getShippingAmount(),
            'tax'               => (float)$quote->getShippingAddress()->getTaxAmount(),
            'grand_total'       => (float)$quote->getGrandTotal(),
            'currency'          => $quote->getQuoteCurrencyCode(),
            'items_count'       => (int)$quote->getItemsCount(),
            'shipping_method'   => $quote->getShippingAddress()->getShippingMethod(),
        ];
    }
}
