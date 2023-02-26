<?php

namespace Database\Seeders;

// use Illuminate\Support\Facades\Log;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Start Role Seeder');

        $role_data = [
            [
                'rl_name' => 'Admin',
            ], [
                'rl_name' => 'User',
            ]
        ];

        Role::insert($role_data);

        $this->command->info('End Role Seeder');
    }
}
