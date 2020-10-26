<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateProduct()
    {
        $d = [
            'title' => 'Fake title',
            'price' => 23.2,
        ];

        $r = $this->postJson('/api/product', $d);
        $r->assertStatus(200);
        $responseData = $r->json();

        $this->assertDatabaseHas('products', [
            'id' => $responseData['data']['product']['id'],
            'title' => $responseData['data']['product']['title'],
            'price' => $responseData['data']['product']['price'],
        ]);
    }

    public function testCreateProductDuplicatedTitle()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = [
            'title' => $p->title,
            'price' => $p->price,
        ];

        $r = $this->postJson('/api/product', $d);
        $r->assertStatus(422);

        $this->assertDatabaseCount('products', 1);
    }

    public function testCreateProductEmptyTitle()
    {
        $d = [
            'title' => '',
            'price' => 10.0,
        ];

        $r = $this->postJson('/api/product', $d);
        $r->assertStatus(422);

        $this->assertDatabaseCount('products', 0);
    }

    public function testCreateProductEmptyPrice()
    {
        $d = [
            'title' => 'Some title',
            'price' => 0,
        ];

        $r = $this->postJson('/api/product', $d);
        $r->assertStatus(422);
        $this->assertEquals('INVALID_FIELD_MANDATORY', data_get($r->json(), 'error.errors.price.code'));
        $this->assertDatabaseCount('products', 0);
    }

    public function testCreateProductPriceLowerThanAccepted()
    {
        $d = [
            'title' => 'Some title',
            'price' => -1,
        ];

        $r = $this->postJson('/api/product', $d);
        $r->assertStatus(422);

        $this->assertEquals('INVALID_PRICE_VALUE', data_get($r->json(), 'error.errors.price.code'));
        $this->assertDatabaseCount('products', 0);
    }
}
