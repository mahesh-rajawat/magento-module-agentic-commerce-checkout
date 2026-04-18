<?php
declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Model\Quote\Address;
use MSR\AgenticUcpCheckout\Api\UcpCheckoutInterface;
use MSR\AgenticUcpCheckout\Model\UcpCart;

class UcpCheckout implements UcpCheckoutInterface
{
    public function __construct(
        private readonly UcpCart                           $ucpCart,
        private readonly CartRepositoryInterface           $cartRepository,
        private readonly ShippingMethodManagementInterface $shippingMethodManagement,
        private readonly PaymentMethodManagementInterface  $paymentMethodManagement,
        private readonly CartTotalRepositoryInterface      $cartTotalRepository,
    ) {}

    public function setShipping(
        string $firstname,
        string $lastname,
        string $street,
        string $city,
        string $regionCode,
        string $postcode,
        string $countryId,
        string $telephone,
        string $shippingMethodCode,
        bool   $billingSameAsShipping = true
    ): array {
        $quote = $this->ucpCart->getOrCreateQuote();

        if ($quote->getItemsCount() === 0) {
            return ['status' => 'error', 'message' => 'Cart is empty. Add items before setting shipping.'];
        }

        $addressData = [
            'firstname'   => $firstname,
            'lastname'    => $lastname,
            'street'      => [$street],
            'city'        => $city,
            'region_code' => $regionCode,
            'postcode'    => $postcode,
            'country_id'  => $countryId,
            'telephone'   => $telephone,
            'email'       => $quote->getCustomerEmail() ?? 'agent@ucp.local',
        ];

        // Set shipping address
        $shipping = $quote->getShippingAddress();
        $shipping->addData($addressData);
        $shipping->setShippingMethod($shippingMethodCode)
            ->setCollectShippingRates(true)
            ->collectShippingRates();

        // Billing — same as shipping by default
        if ($billingSameAsShipping) {
            $billing = $quote->getBillingAddress();
            $billing->addData($addressData);
            $billing->setSameAsBilling(1);
        }

        $quote->setTotalsCollectedFlag(false)->collectTotals();
        $this->cartRepository->save($quote);

        return [
            'status'                   => 'ok',
            'message'                  => 'Shipping address and method set.'
                . ($billingSameAsShipping ? ' Billing set to same as shipping.' : ''),
            'shipping_method'          => $shippingMethodCode,
            'billing_same_as_shipping' => $billingSameAsShipping,
            'totals'                   => $this->formatTotals($quote),
        ];
    }

    /**
     * Set a separate billing address (when different from shipping).
     */
    public function setBilling(
        string $firstname,
        string $lastname,
        string $street,
        string $city,
        string $regionCode,
        string $postcode,
        string $countryId,
        string $telephone
    ): array {
        $quote = $this->ucpCart->getOrCreateQuote();

        $billing = $quote->getBillingAddress();
        $billing->addData([
            'firstname'   => $firstname,
            'lastname'    => $lastname,
            'street'      => [$street],
            'city'        => $city,
            'region_code' => $regionCode,
            'postcode'    => $postcode,
            'country_id'  => $countryId,
            'telephone'   => $telephone,
            'email'       => $quote->getCustomerEmail() ?? 'agent@ucp.local',
        ]);
        $billing->setSameAsBilling(0);

        $quote->setTotalsCollectedFlag(false)->collectTotals();
        $this->cartRepository->save($quote);

        return [
            'status'  => 'ok',
            'message' => 'Billing address set.',
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

    private function formatTotals(\Magento\Quote\Model\Quote $quote): array
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
