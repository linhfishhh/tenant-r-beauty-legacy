<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('users')->insert([
		                               'name' => 'Trang',
		                               'email' => 'tmtrangtv86@gmail.com',
		                               'password' => bcrypt('admin'),
		                               'role_id' => 1,
		                               'created_at' => \Carbon\Carbon::now(),
		                               'updated_at' => \Carbon\Carbon::now()
	                               ]);
    }
}
