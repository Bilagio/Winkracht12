<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $notificationPreferences = [
            'email_reminders' => true,
            'email_marketing' => $this->faker->boolean(70), // 70% chance of opting in
            'email_weather_alerts' => true,
            'reminder_days_before' => $this->faker->randomElement([1, 2, 3]),
        ];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => 'customer',
            'notification_preferences' => $notificationPreferences,
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'date_of_birth' => $this->faker->dateTimeBetween('-70 years', '-16 years'),
            'mobile' => $this->faker->phoneNumber(),
        ];
    }
}
