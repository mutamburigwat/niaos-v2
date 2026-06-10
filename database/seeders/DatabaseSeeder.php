<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        if (\App\Models\User::where('email', 'admin@niaos.co.zw')->doesntExist()) {
            \App\Models\User::create([
                'name' => 'Super Admin',
                'email' => 'admin@niaos.co.zw',
                'password' => bcrypt('admin123'),
                'is_platform_admin' => true,
            ]);
            echo "Platform admin created: admin@niaos.co.zw / admin123\n";
        }
    }
}
