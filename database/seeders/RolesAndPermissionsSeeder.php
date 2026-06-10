<?php

namespace Database\Seeders;

use App\Enums\WorkspaceRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $modulePermissions = [
            'dashboard' => ['view'],
            'customers' => ['view', 'create', 'edit', 'delete'],
            'leads'     => ['view', 'create', 'edit', 'delete'],
            'tasks'     => ['view', 'create', 'edit', 'delete'],
            'quotations' => ['view', 'create', 'edit', 'delete'],
            'files'     => ['view', 'create', 'delete'],
            'activity_logs' => ['view'],
            'workspaces' => ['view', 'create', 'edit', 'delete'],
            'settings'  => ['view', 'edit'],
            'ai'        => ['view'],
            'users'     => ['invite', 'manage'],
        ];

        foreach ($modulePermissions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::create(['name' => "{$module}.{$action}", 'guard_name' => 'web']);
            }
        }

        $rolePermissions = [
            WorkspaceRole::Owner->value => Permission::all()->pluck('name')->toArray(),
            WorkspaceRole::Admin->value => Permission::all()->pluck('name')->toArray(),
            WorkspaceRole::Manager->value => [
                'dashboard.view',
                'customers.view', 'customers.create', 'customers.edit',
                'leads.view', 'leads.create', 'leads.edit',
                'tasks.view', 'tasks.create', 'tasks.edit',
                'quotations.view',
                'files.view', 'files.create',
                'activity_logs.view',
                'settings.view',
                'ai.view',
            ],
            WorkspaceRole::Sales->value => [
                'dashboard.view',
                'customers.view', 'customers.create', 'customers.edit',
                'leads.view', 'leads.create', 'leads.edit',
                'tasks.view', 'tasks.create', 'tasks.edit',
                'quotations.view', 'quotations.create', 'quotations.edit',
                'files.view', 'files.create',
                'activity_logs.view',
            ],
            WorkspaceRole::Support->value => [
                'dashboard.view',
                'customers.view', 'customers.create', 'customers.edit',
                'tasks.view', 'tasks.create', 'tasks.edit',
                'files.view', 'files.create',
                'activity_logs.view',
            ],
            WorkspaceRole::Accounts->value => [
                'dashboard.view',
                'quotations.view', 'quotations.create', 'quotations.edit',
                'tasks.view',
                'activity_logs.view',
            ],
            WorkspaceRole::Procurement->value => [
                'dashboard.view',
                'tasks.view', 'tasks.create', 'tasks.edit',
                'activity_logs.view',
            ],
            WorkspaceRole::Viewer->value => [
                'dashboard.view',
                'customers.view',
                'leads.view',
                'tasks.view',
                'quotations.view',
                'activity_logs.view',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            $role->givePermissionTo($permissions);
        }
    }
}
