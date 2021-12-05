<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\AddressBook;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressBookTest extends WebTestCase
{
    private AddressBook $addressBook;


    protected function setUp(): void
    {
        $this->addressBook = new AddressBook();
    }


    public function testFirstNameWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getFirstName());
    }


    public function testFirstNameWhenSet(): void
    {
        $firstName = uniqid('firstName');
        $this->addressBook->setFirstName($firstName);
        static::assertEquals($firstName, $this->addressBook->getFirstName());
    }


    public function testLastNameWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getLastName());
    }


    public function testLastNameWhenSet(): void
    {
        $lastName = uniqid('lastName');
        $this->addressBook->setLastName($lastName);
        static::assertEquals($lastName, $this->addressBook->getLastName());
    }


    public function testStreetWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getStreet());
    }


    public function testStreetWhenSet(): void
    {
        $street = uniqid('street');
        $this->addressBook->setStreet($street);
        static::assertEquals($street, $this->addressBook->getStreet());
    }


    public function testZipCodeWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getZipCode());
    }


    public function testZipCodeWhenSet(): void
    {
        $zipCode = uniqid('zipCode');
        $this->addressBook->setZipCode($zipCode);
        static::assertEquals($zipCode, $this->addressBook->getZipCode());
    }


    public function testCityWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getCity());
    }


    public function testCityWhenSet(): void
    {
        $city = uniqid('city');
        $this->addressBook->setCity($city);
        static::assertEquals($city, $this->addressBook->getCity());
    }


    public function testCountryWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getCountry());
    }


    public function testCountryWhenSet(): void
    {
        $country = uniqid('country');
        $this->addressBook->setCountry($country);
        static::assertEquals($country, $this->addressBook->getCountry());
    }


    public function testPhoneNumberWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getPhoneNumber());
    }


    public function testPhoneNumberWhenSet(): void
    {
        $phoneNumber = uniqid('phoneNumber');
        $this->addressBook->setPhoneNumber($phoneNumber);
        static::assertEquals($phoneNumber, $this->addressBook->getPhoneNumber());
    }


    public function testBirthdayWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getBirthday());
    }


    public function testBirthdayWhenSet(): void
    {
        $birthday = new \DateTime();
        $this->addressBook->setBirthday($birthday);
        static::assertEquals($birthday, $this->addressBook->getBirthday());
    }


    public function testEmailWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getEmail());
    }


    public function testEmailWhenSet(): void
    {
        $email = uniqid('email');
        $this->addressBook->setEmail($email);
        static::assertEquals($email, $this->addressBook->getEmail());
    }


    public function testPictureWhenNotSet(): void
    {
        static::assertNull($this->addressBook->getPicture());
    }


    public function testPictureWhenSet(): void
    {
        $picture = uniqid('picture');
        $this->addressBook->setPicture($picture);
        static::assertEquals($picture, $this->addressBook->getPicture());
    }
}