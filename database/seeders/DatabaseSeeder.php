<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
			$user = \App\Models\User::create([
				'name'      				=> 'admin',
				'email'     				=> 'admin@admin.com',
				'email_verified_at' => now(),
				'type'     					=> 'ADMIN',
				'document'     			=> '12345678',
				'phone'     				=> '11223344',
				'password'  				=> bcrypt('admin')
			]);
			
			$user = \App\Models\User::create([
				'name'      				=> 'user',
				'email'     				=> 'user@user.com',
				'email_verified_at' => now(),
				'type'     					=> 'USER',
				'document'     			=> '98765432',
				'phone'     				=> '55667788',
				'password'  				=> bcrypt('user')
			]);
				
			\App\Models\Wallet::create([
				'user_id'      			=> $user->id,
				'mount'     				=> 0,
			]);
    }
}
