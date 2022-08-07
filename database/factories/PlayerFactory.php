<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    public function definition()
    {
        $phone = preg_replace('/\D/m', '', fake('pt_BR')->phoneNumber);

        return [
            'name' => fake('pt-Br')->name(),
            'phone' => $phone,
        ];
    }
}
