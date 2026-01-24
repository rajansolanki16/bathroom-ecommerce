<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'mobile' => '+911234567890',
                'state' => 'Rajasthan',
                'country' => 'India',
                'password' => '123456',
                'role' => 'admin',
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'mobile' => '+919876543210',
                'state' => 'Gujrat',
                'country' => 'India',
                'password' => '123456',
                'role' => 'user',
            ],
        ];

        foreach ($users as $userData) {
            if (!User::where('email', $userData['email'])->orWhere('mobile', $userData['mobile'])->exists()) {
                $user = new User();
                $user->name = $userData['name'];
                $user->email = $userData['email'];
                $user->mobile = $userData['mobile'];
                $user->state = $userData['state'];
                $user->country = $userData['country'];
                $user->email_verified_at = now();
                $user->password = Hash::make($userData['password']);
                $user->save();
                $user->assignRole($userData['role']);
            }
        }

    }
}
