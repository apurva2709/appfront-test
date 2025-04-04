<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testDatabase() {
        $this->assertDatabaseHas('users',
            [
                'email' => 'test@example.com',
            ]);
    }

    public function testWithFailDatabase() {
        $this->assertDatabaseHas('users',
            [
                'email' => 'best@example.com',
            ]);
    }

    public function testCreateProduct()
    {
        $data = [
            'name' => "New Product",
            'description' => "This is a product",
            'price' => 10,
            'image' => "https://images.pexels.com/photos/1000084/pexels-photo-1000084.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
        ];
        Product::create($data);
    }
}
