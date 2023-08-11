<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $info = [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt(12345678),
                'is_active' => 1
            ];
            
        //create user
        $user = User::create($info);
    }
}
