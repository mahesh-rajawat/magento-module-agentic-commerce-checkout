<?php
declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api;

interface UcpCheckoutInterface
{
    /**
     * Set shipping address and method on the active cart.
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $street
     * @param string $city
     * @param string $regionCode
     * @param string $postcode
     * @param string $countryId
     * @param string $telephone
     * @param string $shippingMethodCode
     * @param bool $billingSameAsShipping
     * @return mixed[]
     */
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
    ): array;

    /**
     * Set a separate billing address (only needed when different from shipping).
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $street
     * @param string $city
     * @param string $regionCode
     * @param string $postcode
     * @param string $countryId
     * @param string $telephone
     * @return mixed[]
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
    ): array;

    /**
     * Get available shipping methods for the current cart.
     *
     * @return mixed[]
     */
    public function getShippingMethods(): array;

    /**
     * Get available payment methods for the current cart.
     *
     * @return mixed[]
     */
    public function getPaymentMethods(): array;

    /**
     * Get cart totals breakdown (subtotal, shipping, tax, grand total).
     *
     * @return mixed[]
     */
    public function getTotals(): array;
}
