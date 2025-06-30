<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Entity\Product;

class ProductView
{
    public function __construct() {

    }

    public function toArray(Product $product): array
    {
        return array(
            'id' => $product->getId(),
            'uuid' => $product->getUuid(),
            'category' => $product->getCategory(),
            'description' => $product->getDescription(),
            'thumbnail' => $product->getThumbnail(),
            'price' => $product->getPrice()
        );
    }
}