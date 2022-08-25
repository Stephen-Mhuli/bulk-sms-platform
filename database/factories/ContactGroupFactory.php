<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Customer;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactGroupFactory extends Factory
{
    protected $model=ContactGroup::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id'=>Customer::first()->id,
            'contact_id'=>Contact::all()->random()->id,
            'group_id'=>Group::all()->random()->id
        ];
    }
}
