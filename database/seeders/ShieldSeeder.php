<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_activity","view_any_activity","create_activity","update_activity","restore_activity","restore_any_activity","replicate_activity","reorder_activity","delete_activity","delete_any_activity","force_delete_activity","force_delete_any_activity","view_exception","view_any_exception","create_exception","update_exception","restore_exception","restore_any_exception","replicate_exception","reorder_exception","delete_exception","delete_any_exception","force_delete_exception","force_delete_any_exception","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_GeneralSettingsPage","view_author","view_any_author","create_author","update_author","restore_author","restore_any_author","replicate_author","reorder_author","delete_author","delete_any_author","force_delete_author","force_delete_any_author","view_menu","view_any_menu","create_menu","update_menu","restore_menu","restore_any_menu","replicate_menu","reorder_menu","delete_menu","delete_any_menu","force_delete_menu","force_delete_any_menu","view_page","view_any_page","create_page","update_page","restore_page","restore_any_page","replicate_page","reorder_page","delete_page","delete_any_page","force_delete_page","force_delete_any_page","page_HomePageSettingsPage","view_category","view_any_category","create_category","update_category","restore_category","restore_any_category","replicate_category","reorder_category","delete_category","delete_any_category","force_delete_category","force_delete_any_category","view_post","view_any_post","create_post","update_post","delete_post","delete_any_post","publish_post","view_tag","view_any_tag","create_tag","update_tag","restore_tag","restore_any_tag","replicate_tag","reorder_tag","delete_tag","delete_any_tag","force_delete_tag","force_delete_any_tag"]},{"name":"manager","guard_name":"web","permissions":["view_activity","view_any_activity","create_activity","update_activity","restore_activity","restore_any_activity","replicate_activity","reorder_activity","delete_activity","delete_any_activity","force_delete_activity","force_delete_any_activity","view_exception","view_any_exception","create_exception","update_exception","restore_exception","restore_any_exception","replicate_exception","reorder_exception","delete_exception","delete_any_exception","force_delete_exception","force_delete_any_exception","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_GeneralSettingsPage","view_author","view_any_author","create_author","update_author","restore_author","restore_any_author","replicate_author","reorder_author","delete_author","delete_any_author","force_delete_author","force_delete_any_author","view_menu","view_any_menu","create_menu","update_menu","restore_menu","restore_any_menu","replicate_menu","reorder_menu","delete_menu","delete_any_menu","force_delete_menu","force_delete_any_menu","view_page","view_any_page","create_page","update_page","restore_page","restore_any_page","replicate_page","reorder_page","delete_page","delete_any_page","force_delete_page","force_delete_any_page"]}]';
        $directPermissions = '{"80":{"name":"page_HomeSmartPage","guard_name":"web"}}';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
