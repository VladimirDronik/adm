<?php

namespace App\Models;

use App\User;

class UserPermission
{
    /**
     * Если раздел true/false, то все разрешено/запрещено, иначе массив с ключами.
     * Если ключа-действия в подмассиве нет, то это действие в разделе разрешено.
     * Если ключа-раздела нет, то все разрешено.
     * Если ключа-типа пользователя нет, то ему все запрещено.
     *
     * @return array
     */
    public static function permissions(): array
    {
        return [
            User::TYPE_ADMIN => true,

            User::TYPE_USER => [
                'devices' => [
                    'sensors' => false,
                    'controllers' => false,
                    'engineering' => false,
                    'conditioners' => false,
                    'buttons' => false,
                    'locks' => false,
                    'counts' => false,
                    'yandex_stations' => false,
                ],
                'objects' => false,
                'rooms' => false,
                'users' => true,
                'views' => false,
                'scenes' => false,
                'network' => false,
                'menu' => false,
                'scripts' => false,
                'events' => [
                    'show-system' => false,
                    'create-system' => false,
                    'edit-system' => false,
                    'delete-system' => false,
                    'show-hidden' => false,
                    'create-hidden' => false,
                    'edit-hidden' => false,
                    'delete-hidden' => false
                ],
                'settings' => false,
                'logs' => false,
                'graphs' => true
            ],
        ];
    }

    /**
     * Есть ли у пользователя данного типа доступ к $slug
     * Примеры slug: events.system
     *               devices
     *
     * @param string $userType
     * @param string $slug
     * @return bool
     */
    public static function hasAccess(string $userType, string $slug): bool
    {
        $permissions = self::permissions()[$userType] ?? null;

        if (is_null($permissions)) {
            return false;
        }

        if (is_bool($permissions)) {
            return $permissions;
        }

        $slugs = explode('.', $slug);

        $sectionPermissions = $permissions[$slugs[0]] ?? null;

        if (is_null($sectionPermissions)) {
            return true;
        }

        if (is_bool($sectionPermissions)) {
            return $sectionPermissions;
        }

        if (count($slugs) === 1) {
            return true;
        }

        $actionPermissions = $sectionPermissions[$slugs[1]] ?? null;

        if (is_null($actionPermissions)) {
            return true;
        }

        if (is_bool($actionPermissions)) {
            return $actionPermissions;
        }

        return false;
    }

    /**
     * Список всех slugs разрешений для инициализации gates
     *
     * @return array
     */
    public static function slugs(): array
    {
        $permissions = self::permissions();
        $slugs = [];

        foreach ($permissions as $permission) {
            if (!is_array($permission)) {
                continue;
            }

            foreach ($permission as $section => $actions) {
                $slugs[] = $section;
                if (!is_array($actions)) {
                    continue;
                }

                foreach ($actions as $action => $value) {
                    $slugs[] = $section . '.' . $action;
                }
            }
        }

        return array_values(array_unique($slugs));
    }
}