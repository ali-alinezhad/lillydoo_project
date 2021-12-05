<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Repository\AddressBookRepository;
use AppBundle\Service\CommonHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressBookControllerTest extends WebTestCase
{
    private CommonHelper $commonHelper;


    protected function setUp(): void
    {
        $addressBookRepository = $this->createMock(AddressBookRepository::class);
        $this->commonHelper    = new CommonHelper($addressBookRepository);

    }


    public function testIndex(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Welcome', $crawler->filter('.h3')->text());
        $this->assertStringNotContainsString('successfully', $crawler->filter('.success')->text());
    }


    public function testIndexSubmitForm(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('.form_address_book')->form();
        $form['address_book[firstName]']->setValue(uniqid('firstname'));
        $form['address_book[lastName]']->setValue(uniqid('lastName'));
        $form['address_book[street]']->setValue(uniqid('street'));
        $form['address_book[zipCode]']->setValue(123456);
        $form['address_book[city]']->setValue(uniqid('city'));
        $form['address_book[country]']->setValue(uniqid('country'));
        $form['address_book[phoneNumber]']->setValue(123456);
        $form['address_book[birthday][year]']->setValue('2016');
        $form['address_book[birthday][month]']->setValue('1');
        $form['address_book[birthday][day]']->setValue('1');
        $form['address_book[email]']->setValue('test@test.com');
        $client->submit($form);
        $client->followRedirect();
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Welcome', $crawler->filter('.h3')->text());
    }


    public function testUpdateAction(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $maxId   = $crawler->filter(".maxId")->text();
        $crawler = $client->request('GET', '/edit/' . $maxId . '/contact');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('.form_address_book')->form();
        $form['address_book[firstName]']->setValue('First Name');
        $form['address_book[lastName]']->setValue('Last Name');
        $form['address_book[street]']->setValue('Street');
        $form['address_book[zipCode]']->setValue(56789);
        $form['address_book[city]']->setValue('City');
        $form['address_book[country]']->setValue('Country');
        $form['address_book[phoneNumber]']->setValue(567890);
        $form['address_book[birthday][year]']->setValue('2018');
        $form['address_book[birthday][month]']->setValue('2');
        $form['address_book[birthday][day]']->setValue('3');
        $form['address_book[email]']->setValue('test2@test2.com');
        $client->submit($form);
        $this->assertStringContainsString('Welcome', $crawler->filter('.h3')->text());
    }


    public function testDeleteAction(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $maxId = $crawler->filter(".maxId")->text();
        $client->request('GET', '/delete/' . $maxId . '/contact');
        $this->assertStringNotContainsString('/' . $maxId . '/', $client->getResponse()->getContent());
    }


    public function testDeleteActionWhenThereIsError(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->request('GET', '/delete/10000000/contact');
        $this->assertStringNotContainsString('successfully', $crawler->filter('.success')->text());
    }


    public function testShowDetailsAction(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $maxId = $crawler->filter(".maxId")->text();
        $client->request('GET', '/show/contact/' . $maxId . '/details');
        $this->assertStringContainsString('Birthday', $client->getResponse()->getContent());
    }
}
