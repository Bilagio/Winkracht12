<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Location;
use App\Models\LessonPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lessonPackage = LessonPackage::inRandomOrder()->first();
        $price = $lessonPackage ? $lessonPackage->price : 100.00;
        $participants = $this->faker->numberBetween(1, 4);
        $totalPrice = $price * $participants;

        // Generate a date from one week ahead to 3 months in the future
        $date = $this->faker->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d');
        
        // Generate a time between 8:00 and 17:00 (with 30 minute intervals)
        $hours = $this->faker->numberBetween(8, 17);
        $minutes = $this->faker->randomElement(['00', '30']);
        $time = sprintf('%02d:%s', $hours, $minutes);

        return [
            'user_id' => User::where('role', 'customer')->inRandomOrder()->first()->id ?? 1,
            'instructor_id' => User::where('role', 'instructor')->inRandomOrder()->first()->id ?? null,
            'lesson_package_id' => $lessonPackage->id ?? 1,
            'location_id' => Location::inRandomOrder()->first()->id ?? 1,
            'date' => $date,
            'time' => $time,
            'participants' => $participants,
            'total_price' => $totalPrice,
            'status' => $this->faker->randomElement([
                'pending',
                'confirmed',
                'completed',
                'cancelled',
                'instructor_cancelled',
                'weather_cancelled'
            ]),
            'notes' => $this->faker->boolean(30) ? $this->faker->sentence() : null, // 30% chance of having notes
        ];
    }

    /**
     * Configure the factory to generate reservations with 'confirmed' status.
     *
     * @return $this
     */
    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'confirmed',
            ];
        });
    }

    /**
     * Configure the factory to generate reservations with 'pending' status.
     *
     * @return $this
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Configure the factory to generate completed lessons.
     *
     * @return $this
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'date' => $this->faker->dateTimeBetween('-6 months', 'yesterday')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Configure the factory to generate cancelled lessons.
     *
     * @return $this
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => $this->faker->randomElement([
                    'cancelled',
                    'instructor_cancelled',
                    'weather_cancelled'
                ]),
            ];
        });
    }
}
