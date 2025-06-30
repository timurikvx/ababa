<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Entity\Product;

readonly class ProductsView
{
    private ProductView $productView;
    public function __construct()
    {
        $this->productView = new ProductView();
    }

    public function toArray(array $products): array
    {
        return array_map(function(Product $product){
            return $this->productView->toArray($product);
        }, $products);
    }
}
