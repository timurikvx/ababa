<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Exception;
use Raketa\BackendTestTask\Entity\Product;

class ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function getByUuid(string $uuid): ?Product
    {
        try{
            $row = $this->connection->fetchOne(
                "SELECT * FROM products WHERE uuid = " . $uuid,
            );
        } catch (\Doctrine\DBAL\Exception $e) {
            throw new Exception('Database not available');
        }

        if (empty($row)) {
            return null;
        }

        return $this->make($row);
    }

    public function getByCategory(string $category): array
    {
        return array_map(
            static fn (array $row): Product => $this->make($row),
            $this->connection->fetchAllAssociative(
                "SELECT id FROM products WHERE is_active = 1 AND category = " . $category,
            )
        );
    }

    public function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
