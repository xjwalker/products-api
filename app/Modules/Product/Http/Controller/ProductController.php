<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Modules\Product\ProductRepository\ProductRepository;

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

    /**
     * @param DeleteProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(DeleteProductRequest $request)
    {
        $p = $request->get('product');
        return response()->json(['data' => ['product' => $this->productRepository->delete($p)]]);
    }

    public function update(UpdateProductRequest $request)
    {
        $p = $request->get('product');
        $fields = $request->only(['title', 'price']);
        return response()->json(['data' => ['product' => $this->productRepository->update($p, $fields)]]);
    }

    public function get()
    {
        return response()->json(['data' => [$this->productRepository->get()]]);
    }
}
