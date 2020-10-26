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
     * @param string $title
     * @return Product|null
     */
    public function getByTitle(string $title): ?Product
    {
        return Product::where('title', $title)->first();
    }

}
