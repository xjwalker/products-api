<?php

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\UseCases\GetPaginatedProducts;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetProductsTest extends TestCase
{
    use DatabaseTransactions;

    const JSON_STRUCTURE = [
        'data' => [
            'products' => [
                '*' => [
                    'id',
                    'title',
                    'created_at',
                    'updated_at'
                ],
            ],
        ],
    ];

    /**
     * @var ProductRepository
     */
    private $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->app->make(ProductRepository::class);
    }

    public function testGetProducts()
    {
        Product::factory()->count(10)->create();
        $r = $this->getJson('/api/product');
        $r->assertStatus(200);
        $r->assertJsonStructure(self::JSON_STRUCTURE);

        $json = $r->json();
        $this->assertCount(GetPaginatedProducts::LIMIT - 1, $json['data']['products']);
        $records = $this->productRepository->get(3);
        $this->assertEquals($records->last()->id, $json['data']['last_id']);
        $this->assertTrue($json['data']['has_more']);
    }

    public function testGetProductsPaginated()
    {
        Product::factory()->count(5)->create();
        $r = $this->getJson('/api/product');
        $r->assertStatus(200);
        $r->assertJsonStructure(self::JSON_STRUCTURE);

        $json = $r->json();
        $this->assertCount(GetPaginatedProducts::LIMIT - 1, $json['data']['products']);
        $records = $this->productRepository->get(3);
        $this->assertEquals($records->last()->id, $json['data']['last_id']);
        $this->assertTrue($json['data']['has_more']);

        $r = $this->getJson('/api/product?last_id=' . $json['data']['last_id']);
        $r->assertStatus(200);
        $r->assertJsonStructure(self::JSON_STRUCTURE);

        $json = $r->json();
        $this->assertCount(2, $json['data']['products']);
        $records = $this->productRepository->get(5);
        $this->assertEquals($records->last()->id, $json['data']['last_id']);
        $this->assertFalse($json['data']['has_more']);
    }

    public function testGetProductsEmptyProducts()
    {
        $r = $this->getJson('/api/product');
        $r->assertStatus(200);
        $r->assertJsonStructure(self::JSON_STRUCTURE);

        $json = $r->json();

        $this->assertFalse($json['data']['has_more']);
        $this->assertNull($json['data']['last_id']);
        $this->assertEmpty($json['data']['products']);
    }

    public function testGetProductsInvalidId()
    {
        $r = $this->getJson('/api/product?last_id=' . '4321a');
        $r->assertStatus(422);
    }
}
