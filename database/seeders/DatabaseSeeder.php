<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // good
        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 35);

            Review::factory()->count($numReviews)
                ->good()
                ->for($book)
                ->create();
        });

        // average
        Book::factory(34)->create()->each(function ($book) {
            $numReviews = random_int(5, 35);

            Review::factory()->count($numReviews)
                ->average()
                ->for($book)
                ->create();
        });

        // bad
        Book::factory(35)->create()->each(function ($book) {
            $numReviews = random_int(5, 35);

            Review::factory()->count($numReviews)
                ->bad()
                ->for($book)
                ->create();
        });
    }
}
