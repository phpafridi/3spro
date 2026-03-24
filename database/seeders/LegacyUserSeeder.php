<?php
// database/seeders/LegacyUserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LegacyUserSeeder extends Seeder
{
    public function run()
    {
        // All your legacy users from the database
        $legacyUsers = [
            [
                'id' => 47,
                'login_id' => 'afridi',
                'Name' => 'Afridi',
                'password' => 'afridi',
                'email' => 'nil@gmail.com',
                'last_login' => '2022-06-18 11:45:46',
                'mobile' => '03',
                'dept' => 'Management',
                'image' => 'default.jpg',
                'position' => 'IT Manager',
                'last_logout' => '2026-01-27 14:39:13'
            ],
            // Add ALL your users here from the list you showed
            // Copy all 88 users from your old database
        ];

        foreach ($legacyUsers as $user) {
            DB::table('users')->updateOrInsert(
                ['id' => $user['id']],
                [
                    'login_id' => $user['login_id'],
                    'Name' => $user['Name'],
                    'password' => Hash::make($user['password']), // CRITICAL: Hash the password!
                    'email' => $user['email'],
                    'last_login' => $user['last_login'],
                    'mobile' => $user['mobile'],
                    'dept' => $user['dept'],
                    'position' => $user['position'],
                    'last_logout' => $user['last_logout'],
                    'image' => $user['image'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Legacy users imported successfully with hashed passwords!');
    }
}
