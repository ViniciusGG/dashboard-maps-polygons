<?php

namespace Database\Seeders;

use App\Models\LicensePermissions;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RemovePermissionAndLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LicensePermissions::where('permission_id', 13)->delete();
        LicensePermissions::where('permission_id', 14)->delete();
        LicensePermissions::where('permission_id', 15)->delete();
        LicensePermissions::where('permission_id', 16)->delete();
        LicensePermissions::where('permission_id', 17)->delete();
        LicensePermissions::where('permission_id', 18)->delete();
        LicensePermissions::where('permission_id', 19)->delete();
        LicensePermissions::where('permission_id', 20)->delete();

        Permission::where('name', 'view alerts')->delete(); //13
        Permission::where('name', 'create alerts')->delete(); //14
        Permission::where('name', 'edit alerts')->delete(); //15
        Permission::where('name', 'delete alerts')->delete(); //16
        Permission::where('name', 'view message alerts')->delete(); //17
        Permission::where('name', 'create message alerts')->delete(); //18
        Permission::where('name', 'edit message alerts')->delete(); //19
        Permission::where('name', 'delete message alerts')->delete(); //20
    }
}
