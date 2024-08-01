<?php

namespace Database\Seeders;

use App\Models\License;
use App\Models\LicensePermissions;
use App\Models\Role;
use App\Repositories\LicenseRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LicensePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // License 1 -- Basic
        $dataLicense1 = [
            [1, 9, 2],
            [1, 17, 3],
            [1, 21, 4],
        ];

        foreach ($dataLicense1 as $item) {
            LicensePermissions::create([
                'license_id' => $item[0],
                'permission_id' => $item[1],
                'role_id' => $item[2],
            ]);
        }

        //License 4 -- Enterprise
        $dataLicense4 = [
            [4, 9, 2],
            [4, 10, 2],
            [4, 11, 2],
            [4, 12, 2],
            [4, 13, 2],
            [4, 16, 2],
            [4, 18, 2],
            [4, 21, 2],
            [4, 22, 2],
            [4, 5, 3],
            [4, 17, 3],
            [4, 18, 3],
            [4, 21, 3],
            [4, 22, 3],
            [4, 21, 4],
            [4, 22, 4],
            [4, 25, 2],
            [4, 26, 2],
            [4, 27, 2],
            [4, 28, 2],
        ];

        foreach ($dataLicense4 as $item) {
            LicensePermissions::create([
                'license_id' => $item[0],
                'permission_id' => $item[1],
                'role_id' => $item[2],
            ]);
        }
    }
}
