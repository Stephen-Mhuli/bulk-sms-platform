<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\Label;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model=Contact::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id'=>Customer::first()->id,
            'number'=>$this->faker->e164PhoneNumber,
            'first_name'=>$this->faker->firstName,
            'last_name'=>$this->faker->lastName,
            'label_id'=>Label::first()->id
        ];
    }
}
