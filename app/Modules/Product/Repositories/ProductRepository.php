<?php

namespace App\Modules\Product\ProductRepository;

use App\Models\Product;

class ProductRepository
{
    /**
     * @param $fields
     * @return Product
     */
    public function create($fields): Product
    {
        $p = new Product();
        $p->title = $fields['title'];
        $p->price = $fields['price'];
        $p->save();

        return $p;
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public function getById(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * @param string $title
     * @return Product|null
     */
    public function getByTitle(string $title): ?Product
    {
        return Product::where('title', $title)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Product[]
     */
    public function get()
    {
        return Product::query()->get();
    }

    /**
     * @param Product $product
     * @return Product
     * @throws \Exception
     */
    public function delete(Product $product): Product
    {
        /** @var Product $p */
        $product->delete();

        return $product;
    }

    /**
     * @param Product $product
     * @param $fields
     * @return Product
     */
    public function update(Product $product, $fields): Product
    {
        $product->update($fields);

        return $product;
    }
}
