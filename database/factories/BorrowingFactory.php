<?php

namespace Database\Factories;

use App\Models\Copy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $borrowedAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $borrowedAt)->modify('+14 days');

        return [
            'user_id' => User::factory(), 
            'copy_id' => Copy::factory(),
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => null, // or you can randomize it later
        ];
    }
}
