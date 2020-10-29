<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();
        // todo; insert the products listed in the document
        DB::table('products')->insert([
            [
                'title' => 'Fallout',
                'price' => 1.99,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => 'Don’t Starve',
                'price' => 2.99,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => 'Baldur’s Gate',
                'price' => 3.99,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => 'Icewind Dale',
                'price' => 4.99,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => 'Bloodborne ',
                'price' => 5.99,
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ]);
        // todo; create a cart and associate them
        DB::table('carts')->insert([
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        $products = DB::table('products')->get();
        $cart = DB::table('carts')->first();

        foreach ($products as $product) {
            DB::table('cart_products')->insert([
                [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]
            ]);
        }
    }
}
