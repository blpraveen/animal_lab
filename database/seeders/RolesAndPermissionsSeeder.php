<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrNew(['name' => 'Master']);
        Permission::firstOrNew(['name' => 'Breeeding']);
        Permission::firstOrNew(['name' => 'Issue']);
        Permission::firstOrNew(['name' => 'Reports']);
        Permission::firstOrNew(['name' => 'IAEC']);
        Permission::firstOrNew(['name' => 'PI']);
    }
}
