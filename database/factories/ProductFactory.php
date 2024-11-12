<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\GSTType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'category_id' => function () {
                if (! $category = Category::inRandomOrder()->first()) {
                    $category = Category::factory()->create();
                }

                return $category->id;
            },
            'g_s_t_type_id' => function () {
                if (! $gstType = GSTType::inRandomOrder()->first()) {
                    $gstType = GSTType::factory()->create();
                }

                return $gstType->id;
            },
            'sku' => $this->faker->randomNumber(6, true),
            'mrp' => $this->faker->randomNumber(4, true),
            'dp' => $this->faker->randomNumber(3, true),
            'bv' => $this->faker->randomNumber(2, true),
            'opening_stock' => $this->faker->randomNumber(3, true),
            'company_stock' => $this->faker->randomNumber(3, true),
            'description' => $this->faker->paragraph,
            'status' => Product::STATUS_ACTIVE,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $number = mt_rand(1, 30);

            $product->addMedia(database_path("seeders/assets/products/$number.jpg"))
                ->preservingOriginal()
                ->toMediaCollection(Product::MC_PRODUCT_IMAGE);
        });
    }
}
