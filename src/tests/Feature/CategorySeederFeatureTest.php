<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use Database\Seeders\CategorySeeder;

class CategorySeederFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeding_twice_does_not_create_duplicate_categories()
    {
        $this->seed(CategorySeeder::class);
        $firstCount = Category::count();

        $this->seed(CategorySeeder::class);
        $secondCount = Category::count();

        $this->assertEquals($firstCount, $secondCount);
        $this->assertEquals(
            Category::distinct('name')->count('name'),
            Category::count(),
            'カテゴリー名が重複しています'
        );
    }
}
