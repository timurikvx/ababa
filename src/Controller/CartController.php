<?php

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Managers\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;

readonly class CartController
{
    public function __construct(
        private CartView $cartView,
        private CartManager $cartManager,
        private ProductRepository $productRepository
    ) {
    }

    public function cart(RequestInterface $request): ResponseInterface
    {
        $cart = $this->cartManager->getCart();
        if (! $cart) {
            return $this->response(['message' => 'Cart not available'], 404);
        }
        return $this->response($this->cartView->toArray($cart));
    }

    public function addToCart(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $productUuid = strval($rawRequest['productUuid']);
        $quantity = intval($rawRequest['quantity']);

        $cart = $this->cartManager->getCart();
        if(!$cart){
            return $this->response(['status'=>'error', 'message'=>'Cart not available'], 404);
        }
        try{
            $product = $this->productRepository->getByUuid($productUuid);
        }catch (\Exception $ex){
            return  $this->response(['error'=>$ex->getMessage()]);
        }

        $result = $this->cartManager->addToCart($cart, $product, $quantity);
        if(!$result){
            //Не удалось добавить новый элемент в корзину, получаем старую корзину
            $cart = $this->cartManager->getCart();
            if(!$cart){
                return $this->response(['status'=>'error', 'message'=>'Cart not available'], 404);
            }
        }

        return $this->response([
            'status' => 'success',
            'cart' => $this->cartView->toArray($cart)
        ], 200);
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