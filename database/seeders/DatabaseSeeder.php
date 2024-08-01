<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            SatelliteSeeder::class,
            StatusSeeder::class,
            RoleSeeder::class,
            FiltersSeeder::class,
            IndicatorsSeeder::class,
            ServicesSeeder::class,
            LicenseSeeder::class,
            WorkspaceSeeder::class,
            UserSeeder::class,
            WorkspaceMemberSeeder::class,
            AlertSeeder::class,
            AlertMessageSeeder::class,
            AlertMessageAttachmentSeeder::class,
            AlertImageSeeder::class,
            SupportMessageSeeder::class,
            ReportSeeder::class,
            LicensePermissionSeeder::class,
            RemovePermissionAndLicenseSeeder::class,
        ];

        if (env('APP_ENV') === 'production') {
            $seedersToRemove = [
                SatelliteSeeder::class,
                WorkspaceSeeder::class,
                WorkspaceMemberSeeder::class,
                AlertSeeder::class,
                AlertMessageSeeder::class,
                AlertMessageAttachmentSeeder::class,
                AlertImageSeeder::class,
                SupportMessageSeeder::class,
                ReportSeeder::class,
                SupportMessageSeeder::class,
            ];

            $seeders = array_diff($seeders, $seedersToRemove);
        }

        $this->call($seeders);
    }

}
