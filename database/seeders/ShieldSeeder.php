<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_appbanner","view_any_appbanner","create_appbanner","update_appbanner","restore_appbanner","restore_any_appbanner","replicate_appbanner","reorder_appbanner","delete_appbanner","delete_any_appbanner","force_delete_appbanner","force_delete_any_appbanner","view_article","view_any_article","create_article","update_article","restore_article","restore_any_article","replicate_article","reorder_article","delete_article","delete_any_article","force_delete_article","force_delete_any_article","view_categorie","view_any_categorie","create_categorie","update_categorie","restore_categorie","restore_any_categorie","replicate_categorie","reorder_categorie","delete_categorie","delete_any_categorie","force_delete_categorie","force_delete_any_categorie","view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_tag","view_any_tag","create_tag","update_tag","restore_tag","restore_any_tag","replicate_tag","reorder_tag","delete_tag","delete_any_tag","force_delete_tag","force_delete_any_tag","view_user::admin","view_any_user::admin","create_user::admin","update_user::admin","restore_user::admin","restore_any_user::admin","replicate_user::admin","reorder_user::admin","delete_user::admin","delete_any_user::admin","force_delete_user::admin","force_delete_any_user::admin","view_userapp","view_any_userapp","create_userapp","update_userapp","restore_userapp","restore_any_userapp","replicate_userapp","reorder_userapp","delete_userapp","delete_any_userapp","force_delete_userapp","force_delete_any_userapp","view_userkua","view_any_userkua","create_userkua","update_userkua","restore_userkua","restore_any_userkua","replicate_userkua","reorder_userkua","delete_userkua","delete_any_userkua","force_delete_userkua","force_delete_any_userkua"]},{"name":"panel_user","guard_name":"web","permissions":[]}]';
        $directPermissions = '[]';

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
