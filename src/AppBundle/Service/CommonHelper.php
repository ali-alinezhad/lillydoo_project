<?php

namespace AppBundle\Service;

use AppBundle\Repository\AddressBookRepository;

class CommonHelper
{

    private AddressBookRepository $addressBookRepository;


    public function __construct(AddressBookRepository $addressBookRepository)
    {
        $this->addressBookRepository = $addressBookRepository;
    }


    /**
     * @return int
     */
    public function getLastContactId(): int
    {
        return $this->addressBookRepository->getMaxId();
    }
}