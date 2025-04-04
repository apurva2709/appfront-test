<?php

namespace Tests\Feature;

use App\Actions\CreateProduct;
use App\Actions\UpdateProduct;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Http\Request;

class MyProductTest extends TestCase
{
    public function testCreateProduct()
    {
        $data = [
            'name' => "New Product",
            'description' => "This is a product",
            'price' => 10,
        ];

        $request = new Request();
        $request->replace($data);

        $action = new CreateProduct();
        $product = $action->handle($request);

        // Assertions
        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'description' => 'This is a product',
            'price' => 10,
        ]);

        $this->assertInstanceOf(Product::class, $product);
    }

    public function testUpdateProduct()
    {
        // Create a product
        $product = Product::create([
            'name' => 'Old Product',
            'description' => 'Old description',
            'price' => 20,
        ]);

        // Prepare updated data
        $data = [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 25
        ];

        // Create request with updated data
        $request = new Request();
        $request->replace($data);

        // Call the update action
        $action = new UpdateProduct();
        $action->handle($request, $product->id);

        // Refresh product from DB
        $product->refresh();

        // Assertions
        $this->assertEquals('Updated Product', $product->name);
        $this->assertEquals('Updated description', $product->description);
        $this->assertEquals(25, $product->price);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 25,
        ]);
    }
}
