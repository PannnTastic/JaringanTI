<?php

namespace Database\Factories;

use App\Models\Knowledgebase_category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Knowledgebase_category>
 */
class Knowledgebase_categoryFactory extends Factory
{
    protected $model = Knowledgebase_category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kbc_name' => $this->faker->sentence(2, false),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
