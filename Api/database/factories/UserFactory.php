<?php

namespace Database\Factories;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
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

        // Id random
        $idNodelModelo =  $this->faker->unique()->numberBetween(40, 119);
        // Get user with the id from external API
        $user = Controller::apiUserId($idNodelModelo);

        //Return the user generated
        return [
            'state' => $this->faker->randomElement(['0', '1']),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'user_name' => $user['nombre'] . "_" . $user['apellidos'],
            'code' => $user['codigo'],
            'email' => $user['email'],
            'role_id' => ($user['tipo'] == 'Estudiante')  ? 2 : 3,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function createFromApi($idFromApi = null)
    {
        if (!is_null($idFromApi)) {
            $userData = Controller::apiUserId($idFromApi);

            return $this->state(function (array $attributes) use ($userData) {
                return [
                    'user_name' => $userData['nombre'] . "_" . $userData['apellidos'],
                    'code' => $userData['codigo'],
                    'email' => $userData['email'],
                    'role_id' => ($userData['tipo'] == 'Estudiante') ? 2 : 3,
                ];
            });
        }
    }
}
