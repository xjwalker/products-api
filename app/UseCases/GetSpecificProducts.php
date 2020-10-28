<?php

namespace App\UseCases;

use App\Repositories\ProductRepository;

class GetSpecificProducts
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * GetSpecificProducts constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $fields
     * @return \App\Models\Product|\Illuminate\Support\Collection|null
     */
    public function get(array $fields)
    {
        $products = Collect(json_decode($fields['products'], true));
        return $this->productRepository->getById($products->pluck('id')->toArray());
    }
}
