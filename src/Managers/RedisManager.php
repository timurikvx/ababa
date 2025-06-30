<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Managers;

use Redis;
use RedisException;

class RedisManager
{
    readonly private Redis $redis;

    private string $host;

    private int $port;

    private ?string $password;

    private ?int $dbindex;

    private bool $connected;

    public function __construct(string $host, int $port, ?string $password, ?int $dbindex)
    {
        $this->redis = new Redis();
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->dbindex = $dbindex;
        $this->connected = false;
    }

    public function isAvailable(): bool
    {
        return $this->connected;
    }

    /**
     * @throws RedisException
     */
    public function connect(): void
    {
        if (! $this->redis->isConnected() && $this->redis->ping('Pong')) {
            $this->connected = $this->redis->connect(
                $this->host,
                $this->port,
            );
        }
        if ($this->connected) {
            $this->redis->auth($this->password);
            $this->redis->select($this->dbindex);
        }
    }

    /**
     * @throws RedisException
     */
    public function get(string $key): mixed
    {
        return $this->redis->get($key);
    }

    /**
     * @throws RedisException
     */
    public function set(string $key, string $value, $ttl = 86400): void
    {
        $this->redis->setex($key, $ttl, $value);
    }

    /**
     * @throws RedisException
     */
    public function has($key): bool
    {
        return $this->redis->exists($key);
    }
}