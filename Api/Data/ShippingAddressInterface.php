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
     * @return string
     */
    public function getFirstname(): string;

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname(string $firstname): static;

    /**
     * @return string
     */
    public function getLastname(): string;

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): static;

    /**
     * @return string
     */
    public function getStreet(): string;

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street): static;

    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): static;

    /**
     * @return string
     */
    public function getRegionCode(): string;

    /**
     * @param string $regionCode
     * @return $this
     */
    public function setRegionCode(string $regionCode): static;

    /**
     * @return string
     */
    public function getPostcode(): string;

    /**
     * @param string $postcode
     * @return $this
     */
    public function setPostcode(string $postcode): static;

    /**
     * @return string
     */
    public function getCountryId(): string;

    /**
     * @param string $countryId
     * @return $this
     */
    public function setCountryId(string $countryId): static;

    /**
     * @return string
     */
    public function getTelephone(): string;

    /**
     * @param string $telephone
     * @return $this
     */
    public function setTelephone(string $telephone): static;
}
