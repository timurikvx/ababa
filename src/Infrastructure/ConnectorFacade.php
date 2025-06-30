<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Psr\Log\LoggerInterface;

class ConnectorFacade
{

    public Connector $connector;

    public LoggerInterface $logger;

    public function __construct(string $host, int $port, ?string $password, ?int $dbindex)
    {
        $this->connector = new Connector($host, $port, $password, $dbindex);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

}
