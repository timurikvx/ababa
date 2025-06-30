<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Entity\Customer;
use Raketa\BackendTestTask\Entity\Product;

class CustomerView
{
    public function __construct() {

    }

    public function toArray(Customer $customer): array
    {
        return array(
            'id' => $customer->getId(),
            'name' => implode(' ', [
                $customer->getLastName(),
                $customer->getFirstName(),
                $customer->getMiddleName(),
            ]),
            'email' => $customer->getEmail(),
        );
    }
}