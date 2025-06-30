<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Managers;

use Raketa\BackendTestTask\Entity\Cart;
use Raketa\BackendTestTask\Entity\CartItem;
use Raketa\BackendTestTask\Entity\Product;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;
use Ramsey\Uuid\Uuid;

class CartManager extends ConnectorFacade
{
    private const CART_TTL = 86400;

    public function __construct($host, $port, $password)
    {
        parent::__construct($host, $port, $password, 1);
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart): bool
    {
        try {
            $this->connector->set(session_id(), $cart, self::CART_TTL);
            return true;
        } catch (ConnectorException $e) {
            $this->logger->error('Error');
        }
        return false;
    }

    /**
     * @return ?Cart
     */
    public function getCart(): ?Cart
    {
        $session = session_id();
        //session_id подразумевает и false значение
        if($session === false){
            return null;
        }

        try {
            $cart = $this->connector->get($session);
        } catch (ConnectorException $e) {
            $this->logger->error('Error');
            return null;
        }
        //Нет корзины, создаем новую пустую
        if(!$cart){
            return new Cart($session, null, null, null);
        }
        return $cart;
    }

    public function addToCart(Cart $cart, Product $product, int $quantity): bool
    {
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $quantity,
        ));
        return $this->saveCart($cart);
    }

}
