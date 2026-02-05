<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word() . 'の商品',
            'price' => $this->faker->numberBetween(500, 10000),
            'description' => $this->faker->realText(20),
            'condition' => '良好',
            'user_id' => User::factory(),
            'is_sold' => false,
            'image_url' => 'https://example.com/item.png',
        ];
    }
}
