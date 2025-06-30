<?php

namespace Raketa\BackendTestTask\Managers;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class ProductManager
{

    private ProductRepository $productRepository;

    public LoggerInterface $logger;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @throws \Exception
     */
    public function getByUuid(string $uuid): ?Product
    {
        try {
            return $this->productRepository->getByUuid($uuid);
        }catch (\Exception $ex){
            $this->logger->error($ex->getMessage());
            return null;
        }
    }

    public function getByCategory(string $category): array
    {
        return $this->productRepository->getByCategory($category);
    }

}