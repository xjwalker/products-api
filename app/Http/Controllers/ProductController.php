<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\GetProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;
use App\UseCases\GetPaginatedProducts;

class ProductController extends Controller
{
    private $productRepository;
    /**
     * @var GetPaginatedProducts
     */
    private $getPaginatedProducts;

    /**
     * ProductController constructor.
     * @param GetPaginatedProducts $getPaginatedProducts
     * @param ProductRepository $productRepository
     */
    public function __construct(GetPaginatedProducts $getPaginatedProducts, ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->getPaginatedProducts = $getPaginatedProducts;
    }

    /**
     * @param CreateProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param UpdateProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request)
    {
        $p = $request->get('product');
        $fields = $request->only(['title', 'price']);
        return response()->json(['data' => ['product' => $this->productRepository->update($p, $fields)]]);
    }

    /**
     * @param GetProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(GetProductRequest $request)
    {
        $lastId = $request->get('last_id');
        return response()->json(['data' => $this->getPaginatedProducts->get($lastId)]);
    }
}
