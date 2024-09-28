<?php

namespace Database\Factories;
use App\Models\Product;
use App\Models\Company;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'img_path' => 'https://picsum.photos/200/200',
            'product_name' => $this->faker->word, 
            'price' => $this->faker->numberBetween(100, 10000), 
            'stock' => $this->faker->randomDigit, 
            'comment' => $this->faker->sentence,
        ];
    }
}
