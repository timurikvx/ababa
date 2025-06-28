<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;

class CartManager extends ConnectorFacade
{
    //public LoggerInterface $logger;

    public function __construct($host, $port, $password)
    {
        parent::__construct($host, $port, $password, 1);
    }

//    public function setLogger(LoggerInterface $logger): void
//    {
//        $this->logger = $logger;
//    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart): void
    {
        try {
            $this->connector->set(session_id(), $cart);
        } catch (ConnectorException $e) {
            $this->logger->error('Error');
        }
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
            return $this->connector->get($session);
        } catch (ConnectorException $e) {
            $this->logger->error('Error');
        }
        return new Cart($session, null, null, null);
    }
}
