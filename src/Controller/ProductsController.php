<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Managers\ProductManager;
use Raketa\BackendTestTask\View\ProductsView;

readonly class ProductsController
{
    public function __construct(private ProductsView $productsVew, private ProductManager $productManager)
    {

    }

    public function products(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $category = strval($rawRequest['category']);

        $products = $this->productManager->getByCategory($category);
        return $this->response($this->productsVew->toArray($products));
    }

    private function response(array $data, int $code = 200): JsonResponse
    {
        $response = new JsonResponse();
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($code);
    }
}
