<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create permissions
        $permissions = [
            // User Management
            'user:view', 'user:create', 'user:update', 'user:delete', 'user:export', 'user:import', 'user:assign', 'user:print', 'user:manage',

            // Inbound Users
            'inbound_user:view', 'inbound_user:create', 'inbound_user:update', 'inbound_user:delete', 'inbound_user:export', 'inbound_user:import', 'inbound_user:approve', 'inbound_user:assign', 'inbound_user:print',

            // Outbound Users
            'outbound_user:view', 'outbound_user:create', 'outbound_user:update', 'outbound_user:delete', 'outbound_user:export', 'outbound_user:import', 'outbound_user:approve', 'outbound_user:print',

            // Attached Users
            'attached_user:view', 'attached_user:create', 'attached_user:update', 'attached_user:delete', 'attached_user:export', 'attached_user:import', 'attached_user:assign', 'attached_user:print',

            // Buildings
            'building:view', 'building:create', 'building:update', 'building:delete', 'building:export', 'building:import', 'building:assign', 'building:print',

            // Rooms
            'room:view', 'room:create', 'room:update', 'room:delete', 'room:export', 'room:import', 'room:assign', 'room:print',

            // Safes
            'safe:view', 'safe:create', 'safe:update', 'safe:delete', 'safe:export', 'safe:import', 'safe:assign', 'safe:print',

            // Entry Access Lists
            'entry_access_list:view', 'entry_access_list:create', 'entry_access_list:update', 'entry_access_list:delete', 'entry_access_list:export', 'entry_access_list:import', 'entry_access_list:approve', 'entry_access_list:assign', 'entry_access_list:print',

            // Purchase Requests
            'purchase_request:view', 'purchase_request:create', 'purchase_request:update', 'purchase_request:delete', 'purchase_request:export', 'purchase_request:import', 'purchase_request:approve', 'purchase_request:print',

            // Inprocessing Actions
            'inprocessing_action:view', 'inprocessing_action:create', 'inprocessing_action:update', 'inprocessing_action:delete', 'inprocessing_action:export', 'inprocessing_action:import', 'inprocessing_action:assign', 'inprocessing_action:print',

            // Organization
            'organization:view', 'organization:update', 'organization:export', 'organization:import', 'organization:manage', 'organization:print',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $organizationAdmin = Role::create(['name' => 'Organization Admin']);
        $manager = Role::create(['name' => 'Manager']);
        $member = Role::create(['name' => 'Member']);

        // Assign permissions to roles
        // Super Admin gets all permissions
        $superAdmin->givePermissionTo($permissions);

        // Organization Admin gets most permissions except super admin specific ones
        $organizationAdmin->givePermissionTo([
            'user:view', 'user:create', 'user:update', 'user:export', 'user:import', 'user:assign', 'user:print',
            'inbound_user:view', 'inbound_user:create', 'inbound_user:update', 'inbound_user:export', 'inbound_user:import', 'inbound_user:approve', 'inbound_user:assign', 'inbound_user:print',
            'outbound_user:view', 'outbound_user:create', 'outbound_user:update', 'outbound_user:export', 'outbound_user:import', 'outbound_user:approve', 'outbound_user:print',
            'attached_user:view', 'attached_user:create', 'attached_user:update', 'attached_user:export', 'attached_user:import', 'attached_user:assign', 'attached_user:print',
            'building:view', 'building:create', 'building:update', 'building:export', 'building:import', 'building:assign', 'building:print',
            'room:view', 'room:create', 'room:update', 'room:export', 'room:import', 'room:assign', 'room:print',
            'safe:view', 'safe:create', 'safe:update', 'safe:export', 'safe:import', 'safe:assign', 'safe:print',
            'entry_access_list:view', 'entry_access_list:create', 'entry_access_list:update', 'entry_access_list:export', 'entry_access_list:import', 'entry_access_list:approve', 'entry_access_list:assign', 'entry_access_list:print',
            'purchase_request:view', 'purchase_request:create', 'purchase_request:update', 'purchase_request:export', 'purchase_request:import', 'purchase_request:approve', 'purchase_request:print',
            'inprocessing_action:view', 'inprocessing_action:create', 'inprocessing_action:update', 'inprocessing_action:export', 'inprocessing_action:import', 'inprocessing_action:assign', 'inprocessing_action:print',
            'organization:view', 'organization:update', 'organization:export', 'organization:import', 'organization:manage', 'organization:print',
        ]);

        // Manager gets view, create, update, export, print permissions
        $manager->givePermissionTo([
            'user:view', 'user:create', 'user:update', 'user:export', 'user:print',
            'inbound_user:view', 'inbound_user:create', 'inbound_user:update', 'inbound_user:export', 'inbound_user:print',
            'outbound_user:view', 'outbound_user:create', 'outbound_user:update', 'outbound_user:export', 'outbound_user:print',
            'attached_user:view', 'attached_user:create', 'attached_user:update', 'attached_user:export', 'attached_user:print',
            'building:view', 'building:create', 'building:update', 'building:export', 'building:print',
            'room:view', 'room:create', 'room:update', 'room:export', 'room:print',
            'safe:view', 'safe:create', 'safe:update', 'safe:export', 'safe:print',
            'entry_access_list:view', 'entry_access_list:create', 'entry_access_list:update', 'entry_access_list:export', 'entry_access_list:print',
            'purchase_request:view', 'purchase_request:create', 'purchase_request:update', 'purchase_request:export', 'purchase_request:print',
            'inprocessing_action:view', 'inprocessing_action:create', 'inprocessing_action:update', 'inprocessing_action:export', 'inprocessing_action:print',
            'organization:view', 'organization:export', 'organization:print',
        ]);

        // Member gets basic view and print permissions
        $member->givePermissionTo([
            'user:view', 'user:print',
            'inbound_user:view', 'inbound_user:print',
            'outbound_user:view', 'outbound_user:print',
            'attached_user:view', 'attached_user:print',
            'building:view', 'building:print',
            'room:view', 'room:print',
            'safe:view', 'safe:print',
            'entry_access_list:view', 'entry_access_list:print',
            'purchase_request:view', 'purchase_request:print',
            'inprocessing_action:view', 'inprocessing_action:print',
            'organization:view', 'organization:print',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all permissions and roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::truncate();
        Role::truncate();
    }
};
