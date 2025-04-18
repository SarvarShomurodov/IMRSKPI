<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $productManager = Role::create(['name' => 'User']);

        $admin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'view-project',
            'create-project',
            'edit-project',
            'delete-project',
            'view-task',
            'create-task',
            'edit-task',
            'delete-task',
            'view-subtask',
            'create-subtask',
            'edit-subtask',
            'delete-subtask',
            'view-taskassign',
            'create-taskassign',
            'edit-taskassign',
            'delete-taskassign',
        ]);

        $productManager->givePermissionTo([
            'view-project',
            'view-task',
            'view-subtask',
            'view-taskassign'
        ]);
    }
}