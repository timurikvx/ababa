<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Entity\Cart;
use Raketa\BackendTestTask\Managers\RedisManager;
use RedisException;

class Connector
{

    private RedisManager $manager;

    public function __construct(string $host, int $port, string $password, ?int $dbindex = 1)
    {
        $this->manager = new RedisManager($host, $port, $password, $dbindex);
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key): ?Cart
    {
        $this->checkConnection();
        try {
            return unserialize($this->manager->get($key));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, Cart $value, $ttl = 400): void
    {
        $this->checkConnection();
        try {
            $this->manager->set($key, serialize($value), $ttl);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function has($key): bool
    {
        $this->checkConnection();
        try {
            return $this->manager->has($key);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    public function isAvailable(): bool
    {
        return $this->manager->isAvailable();
    }

    /**
     * @throws ConnectorException
     */
    private function checkConnection(): void
    {
        if(!$this->isAvailable()){
            try {
                $this->manager->connect();
            }catch (RedisException $e){
                throw new ConnectorException('Connector error', $e->getCode(), $e);
            }
        }
    }

}
