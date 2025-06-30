<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Entity\Cart;
use Raketa\BackendTestTask\Managers\ProductManager;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class CartView
{
    private CustomerView $customerView;
    private ProductView $productView;

    public function __construct(private ProductManager $productManager)
    {
        $this->customerView = new CustomerView();
        $this->productView = new ProductView();
    }

    public function toArray(Cart $cart): array
    {
        $data = [
            'uuid' => $cart->getUuid(),
            'customer' => $this->customerView->toArray($cart->getCustomer()),
            'payment_method' => $cart->getPaymentMethod(),
        ];

        $total = 0;
        $items = [];
        foreach ($cart->getItems() as $item) {

            $total += $item->getPrice() * $item->getQuantity();
            try{
                $product = $this->productManager->getByUuid($item->getProductUuid());
            }catch (\Exception $ex){
                continue;
            }

            $items[] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                'total' => $total,
                'quantity' => $item->getQuantity(),
                'product' => $this->productView->toArray($product),
            ];
        }
        $data['items'] = $items;
        $data['total'] = $total;

        return $data;
    }
}
