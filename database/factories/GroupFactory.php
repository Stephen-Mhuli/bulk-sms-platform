<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Group;
use App\Models\Label;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model=Group::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id'=>Customer::first()->id,
            'name'=>$this->faker->firstName,
            'import_status'=>'completed',
        ];
    }
}
