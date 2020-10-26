<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use DatabaseTransactions;

    public function testUpdateProduct()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = [
            'id' => $p->id,
            'title' => 'New game title',
            'price' => 9001,
        ];

        $r = $this->patch('/api/product/', $d);
        $r->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $p->id,
            'title' => $p->title,
            'price' => $p->price,
        ]);

        $p->refresh();
        $this->assertDatabaseHas('products', $d);
    }

    public function testUpdateProductOnlyPrice()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = [
            'id' => $p->id,
            'price' => 69,
        ];

        $r = $this->patch('/api/product/', $d);
        $r->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $p->id,
            'title' => $p->title,
            'price' => $p->price,
        ]);

        $p->refresh();
        $this->assertDatabaseHas('products', $d);
    }

    public function testUpdateProductOnlyTitle()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = [
            'id' => $p->id,
            'title' => 'New game title',
        ];

        $r = $this->patch('/api/product/', $d);
        $r->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $p->id,
            'title' => $p->title,
            'price' => $p->price,
        ]);

        $p->refresh();
        $this->assertDatabaseHas('products', $d);
    }

    public function testUpdateProductWithoutId()
    {
        $r = $this->patch('/api/product/', []);
        $r->assertStatus(422);
        $this->assertEquals('INVALID_FIELD_MANDATORY', data_get($r->json(), 'error.errors.id.code'));
    }

    public function testUpdateProductEmptyId()
    {
        $d = ['id' => ''];
        $r = $this->patch('/api/product/', $d);
        $r->assertStatus(422);
        $this->assertEquals('INVALID_FIELD_MANDATORY', data_get($r->json(), 'error.errors.id.code'));
    }

    public function testUpdateProductInvalidId()
    {
        $d = ['id' => -3];

        $r = $this->patch('/api/product/', $d);
        $r->assertStatus(422);

        $this->assertEquals('INVALID_INTEGER', data_get($r->json(), 'error.errors.id.code'));
    }

    public function testUpdateProductNoneExistingProduct()
    {
        $d = ['id' => 7];
        $r = $this->patch('/api/product/', $d);
        $r->assertStatus(422);

        $this->assertEquals('INVALID_EXISTS', data_get($r->json(), 'error.errors.id.code'));
    }

    public function testUpdateProductInvalidPrice()
    {
        /** @var Product $p */
        $p = Product::factory()->create();
        $d = [
            'id' => $p->id,
            'price' => -12,
        ];
        $r = $this->patch('/api/product/', $d);
        $r->assertStatus(422);

        $this->assertEquals('INVALID_PRICE_VALUE', data_get($r->json(), 'error.errors.price.code'));
        $p->refresh();
        $this->assertDatabaseMissing('products', $d);
        $this->assertDatabaseHas('products', $p->toArray());
    }

    public function testUpdateProductInvalidTitleLength()
    {
        /** @var Product $p */
        $p = Product::factory()->create();
        $title = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.' .
            'Lorem Ipsum has been the Lorem Ipsum has been there';
        $d = [
            'id' => $p->id,
            'title' => $title,
            'price' => 10.2,
        ];
        $r = $this->patch('/api/product/', $d);
        $r->assertStatus(422);

        $this->assertEquals('INVALID_MAX', data_get($r->json(), 'error.errors.title.code'));
        $p->refresh();
        $this->assertDatabaseMissing('products', $d);
        $this->assertDatabaseHas('products', $p->toArray());
    }

}
