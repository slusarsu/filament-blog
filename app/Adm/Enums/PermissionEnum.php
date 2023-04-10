<?php

namespace App\Adm\Enums;

enum PermissionEnum: string
{
    //USER PERMISSIONS
    case CREATE_USER = 'create_user';

    case UPDATE_USER = 'update_user';

    case DELETE_USER = 'delete_user';

    case RESTORE_USER = 'restore_user';

    case FORCE_DELETE_USER = 'force_delete_user';

    //PAGE PERMISSIONS
    case CREATE_PAGE = 'create_page';

    case UPDATE_PAGE = 'update_page';

    case DELETE_PAGE = 'delete_page';

    case RESTORE_PAGE = 'restore_page';

    case FORCE_DELETE_PAGE = 'force_delete_page';

    //POST PERMISSIONS
    case CREATE_POST = 'create_post';

    case UPDATE_POST = 'update_post';

    case DELETE_POST = 'delete_post';

    case RESTORE_POST = 'restore_post';

    case FORCE_DELETE_POST = 'force_delete_post';

    //CATEGORY PERMISSIONS
    case CREATE_CATEGORY = 'create_category';

    case UPDATE_CATEGORY = 'update_category';

    case DELETE_CATEGORY = 'delete_category';

    case RESTORE_CATEGORY = 'restore_category';

    case FORCE_DELETE_CATEGORY = 'force_delete_category';

    //TAG PERMISSIONS
    case CREATE_TAG = 'create_tag';

    case UPDATE_TAG = 'update_tag';

    case DELETE_TAG = 'delete_tag';

    case RESTORE_TAG = 'restore_tag';

    case FORCE_DELETE_TAG = 'force_delete_tag';

    //ROLE PERMISSIONS
    case CREATE_ROLE = 'create_role';

    case UPDATE_ROLE = 'update_role';

    case DELETE_ROLE = 'delete_role';

    case RESTORE_ROLE = 'restore_role';

    case FORCE_DELETE_ROLE = 'force_delete_role';

    //PERMISSION PERMISSIONS
    case CREATE_PERMISSION = 'create_permission';

    case UPDATE_PERMISSION = 'update_permission';

    case DELETE_PERMISSION = 'delete_permission';

    case RESTORE_PERMISSION = 'restore_permission';

    case FORCE_DELETE_PERMISSION = 'force_delete_permission';

    /**
     * @return array
     */
    public static function allValues(): array
    {
        return [
            self::CREATE_USER->value,
            self::UPDATE_USER->value,
            self::DELETE_USER->value,
            self::RESTORE_USER->value,
            self::FORCE_DELETE_USER->value,
            self::CREATE_PAGE->value,
            self::UPDATE_PAGE->value,
            self::DELETE_PAGE->value,
            self::RESTORE_PAGE->value,
            self::FORCE_DELETE_PAGE->value,
            self::CREATE_POST->value,
            self::UPDATE_POST->value,
            self::DELETE_POST->value,
            self::RESTORE_POST->value,
            self::FORCE_DELETE_POST->value,
            self::CREATE_CATEGORY->value,
            self::UPDATE_CATEGORY->value,
            self::DELETE_CATEGORY->value,
            self::RESTORE_CATEGORY->value,
            self::FORCE_DELETE_CATEGORY->value,
            self::CREATE_TAG->value,
            self::UPDATE_TAG->value,
            self::DELETE_TAG->value,
            self::RESTORE_TAG->value,
            self::FORCE_DELETE_TAG->value,
            self::CREATE_ROLE->value,
            self::UPDATE_ROLE->value,
            self::DELETE_ROLE->value,
            self::RESTORE_ROLE->value,
            self::FORCE_DELETE_ROLE->value,
            self::CREATE_PERMISSION->value,
            self::UPDATE_PERMISSION->value,
            self::DELETE_PERMISSION->value,
            self::RESTORE_PERMISSION->value,
            self::FORCE_DELETE_PERMISSION->value,
        ];
    }

    /**
     * @return array
     */
    public static function userValues(): array
    {
        return [
            self::CREATE_USER->value,
            self::UPDATE_USER->value,
            self::DELETE_USER->value,
            self::RESTORE_USER->value,
            self::FORCE_DELETE_USER->value,
        ];
    }

    /**
     * @return array
     */
    public static function pageValues(): array
    {
        return [
            self::CREATE_PAGE->value,
            self::UPDATE_PAGE->value,
            self::DELETE_PAGE->value,
            self::RESTORE_PAGE->value,
            self::FORCE_DELETE_PAGE->value,
        ];
    }

    /**
     * @return array
     */
    public static function postValues(): array
    {
        return [
            self::CREATE_POST->value,
            self::UPDATE_POST->value,
            self::DELETE_POST->value,
            self::RESTORE_POST->value,
            self::FORCE_DELETE_POST->value,
        ];
    }

     /**
     * @return array
     */
    public static function categoryValues(): array
    {
        return [
            self::CREATE_CATEGORY->value,
            self::UPDATE_CATEGORY->value,
            self::DELETE_CATEGORY->value,
            self::RESTORE_CATEGORY->value,
            self::FORCE_DELETE_CATEGORY->value,
        ];
    }

    /**
     * @return array
     */
    public static function tagValues(): array
    {
        return [
            self::CREATE_TAG->value,
            self::UPDATE_TAG->value,
            self::DELETE_TAG->value,
            self::RESTORE_TAG->value,
            self::FORCE_DELETE_TAG->value,
        ];
    }

    /**
     * @return array
     */
    public static function roleValues(): array
    {
        return [
            self::CREATE_ROLE->value,
            self::UPDATE_ROLE->value,
            self::DELETE_ROLE->value,
            self::RESTORE_ROLE->value,
            self::FORCE_DELETE_ROLE->value,
        ];
    }

    /**
     * @return array
     */
    public static function permissionValues(): array
    {
        return [
            self::CREATE_PERMISSION->value,
            self::UPDATE_PERMISSION->value,
            self::DELETE_PERMISSION->value,
            self::RESTORE_PERMISSION->value,
            self::FORCE_DELETE_PERMISSION->value,
        ];
    }



}
