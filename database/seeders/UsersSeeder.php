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
        // Delete existing users
        User::whereIn('email', ['admin-ecom-123@yopmail.com', 'supervisor@gmail.com', 'vendor@gmail.com'])->delete();

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin-ecom-123@yopmail.com',
                'username' => 'admin-ecom-123',
                'mobile' => '+911234567890',
                'state' => 'Rajasthan',
                'country' => 'India',
                'password' => '123456',
                'role' => 'admin',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'name' => 'Salesman',
                'email' => 'salesman-ecom-123@yopmail.com',
                'username' => 'salesman-ecom-123',
                'mobile' => '+919876543211',
                'state' => 'Gujarat',
                'country' => 'India',
                'password' => '123456',
                'role' => 'salesman',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'name' => 'Vendor',
                'email' => 'vendor-ecom-123@yopmail.com',
                'username' => 'vendor-ecom-123',
                'mobile' => '+919876543212',
                'state' => 'Maharashtra',
                'country' => 'India',
                'password' => '123456',
                'role' => 'vendor',
                'is_active' => true,
                'is_approved' => true,
            ],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->username = $userData['username'];
            $user->mobile = $userData['mobile'];
            $user->state = $userData['state'];
            $user->country = $userData['country'];
            $user->is_active = $userData['is_active'];
            $user->email_verified_at = now();
            $user->password = Hash::make($userData['password']);
            $user->save();
            $user->assignRole($userData['role']);
        }

    }
}
