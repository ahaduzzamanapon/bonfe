<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssistantRegistrarRoleSeeder extends Seeder
{
    public function run()
    {
        // Avoid duplicate seeding
        if (DB::table('roles')->where('key', 'assistant_registrar')->exists()) {
            $this->command->info('Assistant Registrar role already exists. Skipping.');
            return;
        }

        // Insert role into 'roles' table
        $roleId = DB::table('roles')->insertGetId([
            'name'       => 'Assistant Registrar (Registration)',
            'key'        => 'assistant_registrar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert permission (cat_id can be null/0)
        $permId = DB::table('permissions')->insertGetId([
            'name'       => 'Assistant Registrar',
            'key'        => 'assistant_registrar',
            'cat_id'     => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Link role to permission via roll_has
        DB::table('roll_has')->insert([
            'roll_id'       => $roleId,
            'permission_id' => $permId,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $this->command->info('Assistant Registrar role seeded successfully. Role ID: ' . $roleId);
    }
}
