<?php

declare(strict_types=1);

namespace MSR\AgenticUcpCheckout\Model\Data;

use Magento\Framework\DataObject;
use MSR\AgenticUcpCheckout\Api\Data\ShippingAddressInterface;

/**
 * UCP shipping address data model.
 */
class ShippingAddress extends DataObject implements ShippingAddressInterface
{
    /**
     * Get the first name.
     *
     * @return string
     */
    public function getFirstname(): string
    {
        return (string)$this->getData(self::FIRSTNAME);
    }

    /**
     * Set the first name.
     *
     * @param string $firstname
     * @return static
     */
    public function setFirstname(string $firstname): static
    {
        return $this->setData(self::FIRSTNAME, $firstname);
    }

    /**
     * Get the last name.
     *
     * @return string
     */
    public function getLastname(): string
    {
        return (string)$this->getData(self::LASTNAME);
    }

    /**
     * Set the last name.
     *
     * @param string $lastname
     * @return static
     */
    public function setLastname(string $lastname): static
    {
        return $this->setData(self::LASTNAME, $lastname);
    }

    /**
     * Get the street address.
     *
     * @return string
     */
    public function getStreet(): string
    {
        return (string)$this->getData(self::STREET);
    }

    /**
     * Set the street address.
     *
     * @param string $street
     * @return static
     */
    public function setStreet(string $street): static
    {
        return $this->setData(self::STREET, $street);
    }

    /**
     * Get the city.
     *
     * @return string
     */
    public function getCity(): string
    {
        return (string)$this->getData(self::CITY);
    }

    /**
     * Set the city.
     *
     * @param string $city
     * @return static
     */
    public function setCity(string $city): static
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * Get the region code.
     *
     * @return string
     */
    public function getRegionCode(): string
    {
        return (string)$this->getData(self::REGION_CODE);
    }

    /**
     * Set the region code.
     *
     * @param string $regionCode
     * @return static
     */
    public function setRegionCode(string $regionCode): static
    {
        return $this->setData(self::REGION_CODE, $regionCode);
    }

    /**
     * Get the postcode.
     *
     * @return string
     */
    public function getPostcode(): string
    {
        return (string)$this->getData(self::POSTCODE);
    }

    /**
     * Set the postcode.
     *
     * @param string $postcode
     * @return static
     */
    public function setPostcode(string $postcode): static
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * Get the country ID.
     *
     * @return string
     */
    public function getCountryId(): string
    {
        return (string)$this->getData(self::COUNTRY_ID);
    }

    /**
     * Set the country ID.
     *
     * @param string $countryId
     * @return static
     */
    public function setCountryId(string $countryId): static
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    /**
     * Get the telephone number.
     *
     * @return string
     */
    public function getTelephone(): string
    {
        return (string)$this->getData(self::TELEPHONE);
    }

    /**
     * Set the telephone number.
     *
     * @param string $telephone
     * @return static
     */
    public function setTelephone(string $telephone): static
    {
        return $this->setData(self::TELEPHONE, $telephone);
    }
}
