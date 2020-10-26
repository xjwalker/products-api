<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(CreateProductRequest $request)
    {
        $fields = $request->only(['title', 'price']);
        return response()->json(['data' => ['product' => $this->productRepository->create($fields)]]);
    }

}
