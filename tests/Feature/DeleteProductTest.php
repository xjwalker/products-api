<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use DatabaseTransactions;

    public function testDeleteProduct()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = ['id' => $p->id];
        $r = $this->deleteJson('/api/product', $d);
        $r->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $p->id,
            'title' => $p->title,
            'price' => $p->price,
        ]);
    }

    public function testDeleteProductEmptyId()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = ['id' => ''];
        $r = $this->deleteJson('/api/product', $d);
        $r->assertStatus(422);

        $this->assertDatabaseHas('products', [
            'id' => $p->id,
            'title' => $p->title,
            'price' => $p->price,
        ]);
    }

    public function testDeleteProductNoneExistingProduct()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = ['id' => 1234526];
        $r = $this->deleteJson('/api/product', $d);
        $r->assertStatus(422);

        $this->assertEquals('INVALID_EXISTS', data_get($r->json(), 'error.errors.id.code'));
        $this->assertDatabaseHas('products', [
            'id' => $p->id,
            'title' => $p->title,
            'price' => $p->price,
        ]);
    }

    public function testDeleteProductInvalidId()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = ['id' => "invalid field"];
        $r = $this->deleteJson('/api/product', $d);
        $r->assertStatus(422);

        $this->assertDatabaseHas('products', [
            'id' => $p->id,
            'title' => $p->title,
            'price' => $p->price,
        ]);
    }

    public function testDeleteProductInvalidIdValue()
    {
        /** @var Product $p */
        $p = Product::factory()->create();

        $d = ['id' => -1];
        $r = $this->deleteJson('/api/product', $d);
        $r->assertStatus(422);
        $this->assertEquals('INVALID_PRICE_VALUE', data_get($r->json(), 'error.errors.id.code'));

        $this->assertDatabaseHas('products', [
            'id' => $p->id,
            'title' => $p->title,
            'price' => $p->price,
        ]);
    }
}
