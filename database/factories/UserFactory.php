<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => User::TYPE_USER,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'active' => true,
            'timezone' => config('app.timezone'),
            'last_login_at' => null,
            'last_login_ip' => null,
            'to_be_logged_out' => false,
            'provider' => null,
            'provider_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => User::TYPE_ADMIN,
        ]);
    }

    public function user(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => User::TYPE_USER,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'active' => false,
        ]);
    }
}
