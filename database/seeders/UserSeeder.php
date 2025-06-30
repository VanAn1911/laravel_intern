<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\UserStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Tạo tài khoản Admin
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Super',
            'email' => 'superadmin@khgc.com',
            'password' => Hash::make('Abcd@1234'),
            'status' => UserStatus::APPROVED,
            'role' => RoleEnum::ADMIN,
        ]);
        User::factory(10)->create();
    }
}
