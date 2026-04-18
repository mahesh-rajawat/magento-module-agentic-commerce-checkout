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
    public function getFirstname(): string
    {
        return (string)$this->getData(self::FIRSTNAME);
    }

    public function setFirstname(string $firstname): static
    {
        return $this->setData(self::FIRSTNAME, $firstname);
    }

    public function getLastname(): string
    {
        return (string)$this->getData(self::LASTNAME);
    }

    public function setLastname(string $lastname): static
    {
        return $this->setData(self::LASTNAME, $lastname);
    }

    public function getStreet(): string
    {
        return (string)$this->getData(self::STREET);
    }

    public function setStreet(string $street): static
    {
        return $this->setData(self::STREET, $street);
    }

    public function getCity(): string
    {
        return (string)$this->getData(self::CITY);
    }

    public function setCity(string $city): static
    {
        return $this->setData(self::CITY, $city);
    }

    public function getRegionCode(): string
    {
        return (string)$this->getData(self::REGION_CODE);
    }

    public function setRegionCode(string $regionCode): static
    {
        return $this->setData(self::REGION_CODE, $regionCode);
    }

    public function getPostcode(): string
    {
        return (string)$this->getData(self::POSTCODE);
    }

    public function setPostcode(string $postcode): static
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    public function getCountryId(): string
    {
        return (string)$this->getData(self::COUNTRY_ID);
    }

    public function setCountryId(string $countryId): static
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    public function getTelephone(): string
    {
        return (string)$this->getData(self::TELEPHONE);
    }

    public function setTelephone(string $telephone): static
    {
        return $this->setData(self::TELEPHONE, $telephone);
    }
}
