<?php

namespace Database\Seeders;

use App\Models\Workspace;
use App\Models\WorkspaceAreaPoint;
use Illuminate\Database\Seeder;


class WorkspaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Workspace::create([
            'name' => 'Oeiras',
            'admin_email' => 'davidmiller@buzzvel.com',
            'admin_name' => 'David Miller Smith',
            'license_id' => 4,
            'code_azulfy' => '123',
            'status' => 'approved',
            'region_map_area' => '{"id": "9630462fb6f4ec0fcf5e5f9496e313af", "type": "Feature", "geometry": {"type": "Polygon", "coordinates": [[[-9.322865057439458, 38.67529742176578], [-9.300168647246352, 38.6900027516148], [-9.294494544697471, 38.686518629371506], [-9.318174465999618, 38.67258044380142], [-9.322865057439458, 38.67529742176578]]]}, "properties": {}}'
        ]);

        Workspace::create([
            'name' => 'Vizela',
            'admin_email' => 'emilyjohnson@buzzvel.com',
            'admin_name' => 'Emily Johnson',
            'license_id' => 2,
            'status' => 'approved'
        ]);

        Workspace::create([
            'name' => 'Vila Rea',
            'admin_email' => 'johnclark@buzzvel.com',
            'admin_name' => 'John Clak',
            'license_id' => 3,
            'status' => 'approved'
        ]);

        Workspace::create([
            'name' => 'Cascais',
            'admin_email' => 'laurenking@buzzvel.com',
            'admin_name' => 'Lauren King',
            'license_id' => 4,
            'status' => 'approved'
        ]);

        Workspace::create([
            'name' => 'Vila do Conde',
            'admin_email' => 'sarahhall@buzzvel.com',
            'admin_name' => 'Sarah Hall',
            'license_id' => 1,
            'status' => 'approved'
        ]);

        Workspace::create([
            'name' => 'Oeiras',
            'admin_email' => 'benjaminharris@buzzvel.com',
            'admin_name' => 'Benjamin Harris',
            'license_id' => 1,
            'status' => 'approved'
        ]);


        Workspace::create([
            'name' => 'Olhão',
            'admin_email' => 'vicotiascott@buzzvel.com',
            'admin_name' => 'Victoria Scott',
            'license_id' => 1,
            'status' => 'approved'
        ]);

        Workspace::create([
            'name' => 'Cascais',
            'admin_email' => 'amandawilson@buzzvel.com',
            'admin_name' => 'Amanda Wilson',
            'license_id' => 1,
            'status' => 'approved'
        ]);
    }
}


