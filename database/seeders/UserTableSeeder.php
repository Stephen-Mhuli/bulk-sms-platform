<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [[
            'name'           => 'Admin',
            'email'          => 'steverobert@gmail.com',
            'password'       => bcrypt('stephen12345'),
            'remember_token' => null,
            'created_at'     => now(),
            'updated_at'     => now(),
            'deleted_at'     => null,
        ]];

        \App\Models\User::insert($users);
    }
}
