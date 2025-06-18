<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Theater;
use App\Models\Showtime;
use Illuminate\Database\Seeder;

class CinemaSeeder extends Seeder
{
    public function run()
    {
        // Create sample theaters
        $theaters = [
            ['name' => 'Theater 1', 'capacity' => 150, 'type' => 'regular', 'is_active' => true],
            ['name' => 'Theater 2', 'capacity' => 200, 'type' => 'imax', 'is_active' => true],
            ['name' => 'Theater 3', 'capacity' => 100, 'type' => 'vip', 'is_active' => true],
            ['name' => 'Theater 4', 'capacity' => 80, 'type' => '4dx', 'is_active' => true],
        ];

        foreach ($theaters as $theater) {
            Theater::create($theater);
        }

        // Create sample movies
        $movies = [
            [
                'title' => 'Avengers: Endgame',
                'genre' => 'Action, Adventure, Drama',
                'duration' => 181,
                'release_date' => '2019-04-26',
                'rating' => 'PG-13',
                'description' => 'After the devastating events of Avengers: Infinity War, the universe is in ruins.',
                'poster_url' => 'https://via.placeholder.com/300x450/ff6b6b/ffffff?text=Avengers',
                'price' => 12.50,
                'status' => 'now_showing'
            ],
            [
                'title' => 'Spider-Man: No Way Home',
                'genre' => 'Action, Adventure, Sci-Fi',
                'duration' => 148,
                'release_date' => '2021-12-17',
                'rating' => 'PG-13',
                'description' => 'With Spider-Man\'s identity now revealed, Peter asks Doctor Strange for help.',
                'poster_url' => 'https://via.placeholder.com/300x450/4ecdc4/ffffff?text=Spider-Man',
                'price' => 11.00,
                'status' => 'now_showing'
            ],
            [
                'title' => 'The Batman',
                'genre' => 'Action, Crime, Drama',
                'duration' => 176,
                'release_date' => '2022-03-04',
                'rating' => 'PG-13',
                'description' => 'When the Riddler, a sadistic serial killer, begins murdering key political figures in Gotham.',
                'poster_url' => 'https://via.placeholder.com/300x450/45b7d1/ffffff?text=Batman',
                'price' => 13.00,
                'status' => 'coming_soon'
            ]
        ];

        foreach ($movies as $movie) {
            Movie::create($movie);
        }

        // Create sample showtimes
        $movies = Movie::all();
        $theaters = Theater::all();

        foreach ($movies as $movie) {
            foreach ($theaters->take(2) as $theater) {
                Showtime::create([
                    'movie_id' => $movie->id,
                    'theater_id' => $theater->id,
                    'show_date' => now()->addDays(rand(0, 7)),
                    'show_time' => sprintf('%02d:%02d', rand(10, 22), rand(0, 1) * 30),
                    'available_seats' => $theater->capacity,
                    'ticket_price' => $movie->price + rand(0, 5),
                ]);
            }
        }
    }
}