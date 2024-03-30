<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate book titles with various formats
        $titles = [
            'The '.$this->faker->word.' '.$this->faker->word,
            $this->faker->sentence(3),
            ucfirst($this->faker->words(2, true)),
            $this->faker->catchPhrase(),
            $this->faker->sentence(4, true),
        ];

        return [
            'title' => $this->faker->randomElement($titles),
            'description' => $this->faker->text(200),
            'isbn' => $this->faker->numberBetween(100000,90000),
            'publication_date' => $this->faker->date('Y-m-d'),
        ];
    }
}
