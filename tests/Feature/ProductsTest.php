<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_does_not_contains_products()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('No products found');
    }

    public function test_products_page_does_contains_products()
    {
        $product = Product::create([
            'name' => 'Product test',
            'price' => 19.99,
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('No products found');
        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function test_products_page_does_not_contains_the_6th_record()
    {
        $products = Product::factory(6)->create();
        $lastProduct = $products->last();

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('No products found');
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }

    public function test_product_save_has_failed_and_redirect_back()
    {
        $response = $this->post('products', [
            'name' => 'Pr',
            'price' => ''
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['name', 'price']);
    }

    public function test_product_saved_successfully()
    {
        $product = [
            'name' => 'Product test',
            'price' => 19.99,
        ];

        $response = $this->post('products', $product);
        $response->assertStatus(302);

        $lastProduct = Product::latest()->first();
        $response->assertRedirectToRoute('products.show', ['product' => $lastProduct->id]);

        $this->assertDatabaseHas('products', $lastProduct->toArray());
        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price'], $lastProduct->price);
    }

    public function test_product_edit_form_has_correct_value_in_product_show_page()
    {
        $product = Product::factory()->create();

        $response = $this->get('products/' . $product->id);
        $response->assertStatus(200);
        $response->assertSee('value="' . $product->name . '"', false);
        $response->assertSee('value="' . $product->price . '"', false);
        $response->assertViewHas('product', $product);
    }

    public function test_product_update_has_failed_and_redirect_back()
    {
        $product = Product::factory()->create();

        $response = $this->put('products/' . $product->id, [
            'name' => 'Pr',
            'price' => ''
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['name', 'price']);
    }

    public function test_product_updated_successfully()
    {
        $product = Product::factory()->create();

        $pendingValues = [
            'name' => 'Product test edited',
            'price' => 24.99
        ];

        $response = $this->put('products/' . $product->id, $pendingValues);

        $response->assertStatus(302);

        $response->assertValid(['name', 'price']);
        $this->assertDatabaseHas('products', $pendingValues);

        $response->assertRedirectToRoute('products.show', ['product' => $product->id]);
    }

    public function test_product_deleted_successfully()
    {
        $product = Product::factory()->create();

        $response = $this->delete('products/' . $product->id);

        $response->assertStatus(302);
        $response->assertRedirect('products');

        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseCount('products', 0);
    }
}
