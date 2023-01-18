<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    public function createUser(): User
    {
        return User::factory()->create();
    }

    public function test_user_can_access_to_page_products_after_auth()
    {
        $response = $this->actingAs($this->user)->get('dashboard/products');

        $this->assertAuthenticated();

        $response->assertStatus(200);
        $response->assertSee('Products');
        $response->assertSee($this->user->first_name);
        $response->assertSee($this->user->last_name);
    }

    public function test_products_page_does_not_contains_products()
    {
        $response = $this->actingAs($this->user)->get('dashboard/products');

        $response->assertStatus(200);
        $response->assertSee('No products found');
    }

    public function test_products_page_does_contains_products()
    {
        $product = Product::create([
            'name' => 'Product test',
            'price' => 19.99,
        ]);

        $response = $this->actingAs($this->user)->get('dashboard/products');

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

        $response = $this->actingAs($this->user)->get('dashboard/products');

        $response->assertStatus(200);
        $response->assertDontSee('No products found');
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }

    public function test_user_can_access_to_create_product_page()
    {
        $response = $this->actingAs($this->user)->get('dashboard/products/create');

        $response->assertStatus(200);
        $response->assertSee('Add product');
    }

    public function test_product_save_has_failed_and_redirect_back()
    {
        $response = $this->actingAs($this->user)->post('dashboard/products', [
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

        $response = $this->actingAs($this->user)->post('dashboard/products', $product);
        $response->assertStatus(302);

        $lastProduct = Product::latest()->first();
        $response->assertRedirectToRoute('products.show', ['product' => $lastProduct->id]);

        $this->assertDatabaseHas('products', $lastProduct->toArray());
        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price'], $lastProduct->price);
    }

    public function test_user_can_access_to_edit_product_page()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->get('dashboard/products/' . $product->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Update product informations');
    }

    public function test_product_edit_form_has_correct_value_in_product_edit_product_page()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->get('dashboard/products/' . $product->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('value="' . $product->name . '"', false);
        $response->assertSee('value="' . $product->price . '"', false);
        $response->assertViewHas('product', $product);
    }

    public function test_product_update_has_failed_and_redirect_back()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->put('dashboard/products/' . $product->id, [
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

        $response = $this->actingAs($this->user)->put('dashboard/products/' . $product->id, $pendingValues);

        $response->assertStatus(302);

        $response->assertValid(['name', 'price']);
        $this->assertDatabaseHas('products', $pendingValues);

        $response->assertRedirectToRoute('products.show', ['product' => $product->id]);
    }

    public function test_product_deleted_successfully()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->delete('dashboard/products/' . $product->id);

        $response->assertStatus(302);
        $response->assertRedirect('dashboard/products');

        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseCount('products', 0);
    }
}
