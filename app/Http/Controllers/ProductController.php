<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\GetProductRequest;
use App\Http\Requests\GetSpecificProductsRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;
use App\UseCases\GetPaginatedProducts;
use App\UseCases\GetSpecificProducts;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var GetPaginatedProducts
     */
    private $getPaginatedProducts;

    /**
     * @var GetSpecificProducts
     */
    private $getSpecificProducts;

    /**
     * ProductController constructor.
     * @param GetPaginatedProducts $getPaginatedProducts
     * @param GetSpecificProducts $getSpecificProducts
     * @param ProductRepository $productRepository
     */
    public function __construct(
        GetPaginatedProducts $getPaginatedProducts,
        GetSpecificProducts $getSpecificProducts,
        ProductRepository $productRepository
    ) {
        $this->getPaginatedProducts = $getPaginatedProducts;
        $this->getSpecificProducts = $getSpecificProducts;
        $this->productRepository = $productRepository;
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

    /**
     * @param GetSpecificProductsRequest $getSpecificProducts
     * @return \App\Models\Product|\Illuminate\Support\Collection|null
     */
    public function getSpecificProducts(GetSpecificProductsRequest $getSpecificProducts)
    {
        return $this->getSpecificProducts->get($getSpecificProducts->only('products'));
    }
}
