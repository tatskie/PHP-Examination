<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'admin']);

        /** 
	     * @var \App\User $user 
	     */
	    $admin = factory(\App\User::class)->create([
	        'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
	    ]);
	    
	    $admin->assignRole('admin');
    }
}
