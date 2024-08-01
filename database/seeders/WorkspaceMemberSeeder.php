<?php

namespace Database\Seeders;

use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Database\Seeder;


class WorkspaceMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => 1,
            'role_id' => 1,
        ]);

        WorkspaceMember::create([
            'workspace_id' => Workspace::first()->id,
            'user_id' => 2,
            'role_id' => 2,
        ]);
    }
}


