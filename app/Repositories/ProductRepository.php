<?php

namespace App\Repositories;

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

}
