<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates (or updates) an administrator account and the admin role.
     */
    public function run()
    {
        $role = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrateur du système']
        );

        $email = 'admin@example.com';
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = new User();
            $user->name = 'Administrator';
            $user->email = $email;
            $user->password = Hash::make('Secret123!');
            $user->email_verified_at = Carbon::now();
        }

        // Ensure role relation / fields are set for compatibility
        $user->role_id = $role->id;
        if (Schema::hasColumn('users', 'role')) {
            $user->role = 'admin';
        }
        $user->save();

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info('Admin user created/updated: ' . $user->email . ' (password: Secret123!)');
        }
    }
}
