<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Api\Data;

/**
 * UCP shipping address data interface.
 */
interface ShippingAddressInterface
{
    public const FIRSTNAME    = 'firstname';
    public const LASTNAME     = 'lastname';
    public const STREET       = 'street';
    public const CITY         = 'city';
    public const REGION_CODE  = 'region_code';
    public const POSTCODE     = 'postcode';
    public const COUNTRY_ID   = 'country_id';
    public const TELEPHONE    = 'telephone';

    /**
     * Get the first name.
     *
     * @return string
     */
    public function getFirstname(): string;

    /**
     * Set the first name.
     *
     * @param string $firstname
     * @return $this
     */
    public function setFirstname(string $firstname): static;

    /**
     * Get the last name.
     *
     * @return string
     */
    public function getLastname(): string;

    /**
     * Set the last name.
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): static;

    /**
     * Get the street address.
     *
     * @return string
     */
    public function getStreet(): string;

    /**
     * Set the street address.
     *
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street): static;

    /**
     * Get the city.
     *
     * @return string
     */
    public function getCity(): string;

    /**
     * Set the city.
     *
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): static;

    /**
     * Get the region code.
     *
     * @return string
     */
    public function getRegionCode(): string;

    /**
     * Set the region code.
     *
     * @param string $regionCode
     * @return $this
     */
    public function setRegionCode(string $regionCode): static;

    /**
     * Get the postcode.
     *
     * @return string
     */
    public function getPostcode(): string;

    /**
     * Set the postcode.
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode(string $postcode): static;

    /**
     * Get the country ID.
     *
     * @return string
     */
    public function getCountryId(): string;

    /**
     * Set the country ID.
     *
     * @param string $countryId
     * @return $this
     */
    public function setCountryId(string $countryId): static;

    /**
     * Get the telephone number.
     *
     * @return string
     */
    public function getTelephone(): string;

    /**
     * Set the telephone number.
     *
     * @param string $telephone
     * @return $this
     */
    public function setTelephone(string $telephone): static;
}
