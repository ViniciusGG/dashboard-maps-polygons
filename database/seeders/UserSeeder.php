<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (env('APP_ENV') === 'production') {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'jonatan@azulfy.com',
                'password' => Hash::make('password'),
                'password_expires_at' => now()->addYears(1),
                'language' => 'pt',
            ]);
            $user->assignRole('super_admin');

            return;
        }
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@buzzvel.com',
            'password' => Hash::make('password'),
            'password_expires_at' => now()->addYears(1),
            'language' => 'pt',
        ]);

        $user->assignRole('super_admin');

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),

        ]);
        // $user->assignRole('admin');

        $user = User::create([
            'name' => 'Technicians',
            'email' => 'technicians@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);
        // $user->assignRole('technicians');

        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 3,

        ]);

        $user = User::create([
            'name' => 'External Service Provider',
            'email' => 'externalserviceprovider@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);
        $user->assignRole('external_service_provider');

        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 4,

        ]);

        $user = User::create([
            'name' => 'David Miller',
            'email' => 'davidmiller@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);
        $user->assignRole('admin');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 2,

        ]);

        $user = User::create([
            'name' => 'Amanda Wilson',
            'email' => 'amandawilson@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);

        $user->assignRole('admin');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 2,

        ]);

        $user = User::create([
            'name' => 'Matthew White',
            'email' => 'mattew@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);

        $user->assignRole('admin');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 2,

        ]);

        $user = User::create([
            'name' => 'Emily Johnson',
            'email' => 'emilyjohson@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);

        $user->assignRole('technicians');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 3,

        ]);

        $user = User::create([
            'name' => 'Elizabeth Taylor',
            'email' => 'elizabeth@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);

        $user->assignRole('technicians');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 3,

        ]);

        $user = User::create([
            'name' => 'John Clark',
            'email' => 'johnclak@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);

        $user->assignRole('technicians');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 3,

        ]);

        $user = User::create([
            'name' => 'Sarah Hall',
            'email' => 'sarahhall@buzzvel',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),

        ]);

        $user->assignRole('technicians');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 3,

        ]);

        $user = User::create([
            'name' => 'Joseph Lee',
            'email' => 'josehlee@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);

        $user->assignRole('external_service_provider');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 4,

        ]);

        $user = User::create([
            'name' => 'Thomas Harris',
            'email' => 'thomasharris@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);

        $user->assignRole('external_service_provider');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 4,

        ]);

        $user = User::create([
            'name' => 'Michael Martin',
            'email' => 'michaelmartins@buzzvel.com',
            'password' => Hash::make('password'),
            'language' => 'pt',
            'password_expires_at' => now()->addYears(1),
        ]);

        $user->assignRole('external_service_provider');
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => $user->id,
            'role_id' => 4,

        ]);
    }
}
