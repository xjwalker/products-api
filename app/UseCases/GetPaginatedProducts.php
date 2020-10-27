<?php

namespace App\UseCases;


use App\Repositories\ProductRepository;

class GetPaginatedProducts
{
    const LIMIT = 4;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * GetPaginatedProducts constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param int|null $lastId
     * @return array
     */
    public function get(int $lastId = null)
    {
        $result = [
            'products' => [],
            'last_id' => null,
            'has_more' => false,
        ];

        $products = $this->productRepository->get(self::LIMIT, $lastId);
        if (!$products->isEmpty()) {
            if ($products->count() == self::LIMIT) {
                $result['has_more'] = true;
                $products->pop();
            }
            $result['last_id'] = $products->last()->id;
            $result['products'] = $products;
        }

        return $result;
    }
}
