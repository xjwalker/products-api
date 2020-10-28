<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetSpecificProductsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     *
     */
    public function testGetSpecificProducts()
    {
        /** @var Product $product1 */
        /** @var Product $product3 */
        /** @var Product $product2 */
        $product1 = Product::factory()->create(['created_at' => \Carbon\Carbon::now()->subHours(2)]);
        $product2 = Product::factory()->create(['created_at' => \Carbon\Carbon::now()->subHours(1)]);
        $product3 = Product::factory()->create(['created_at' => \Carbon\Carbon::now()]);
        $data = [
            [
                'id' => $product1->id,
            ], [
                'id' => $product2->id,
            ], [
                'id' => $product3->id,
            ],
        ];

        $r = $this->getJson('/api/product/list?products=' . json_encode($data, true));
        $r->assertStatus(200);
    }
}
