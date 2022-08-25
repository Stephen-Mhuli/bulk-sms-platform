<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\SmsTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsTemplateFactory extends Factory
{
    protected $model=SmsTemplate::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id'=>Customer::first()->id,
            'title'=>'temp-1',
            'status'=>'active',
            'body'=>'Hi, Test Template'
        ];
    }
}
