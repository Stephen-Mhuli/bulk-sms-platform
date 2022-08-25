<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = [[
            'first_name'     => 'Stephen',
            'last_name'      => 'Mhuli',
            'email'          => 'stephenmhuli@gmail.com',
            'password'       => bcrypt('stephen12345'),
            'status'         => 'active',
            'email_verified_at'  => now()
        ]];
        
        \App\Models\Customer::insert($customers);
    }
}
