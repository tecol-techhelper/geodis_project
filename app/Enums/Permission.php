<?php

namespace App\Enums;

enum Permission: string
{
    case CreateUsers = 'create_users';
    case ViewUsers = 'view_users';
    case ViewUserDetails = 'view_user_details';
    case UpdateUsers = 'update_users';
    case UpdateUserStatus = 'update_user_status';
    case DeleteUsers = 'delete_users';
    case ViewServices = 'view_services';
    case EditServices = 'edit_services';
    case UploadFile = 'upload_files';
    case DeleteServices = 'delete_services';
    case PurgeServices = 'purge_services';

    public function label(): string
    {
        return match ($this) {
            self::CreateUsers => 'Crear Usuario',
            self::ViewUsers => 'Consultar usuarios',
            self::ViewUserDetails => 'Consultar usuario',
            self::UpdateUsers => 'Editar usuarios',
            self::UpdateUserStatus => 'Estados de estado de usuarios',
            self::DeleteUsers => 'Eliminar usuarios',
            self::ViewServices => 'Consultar servicios',
            self::EditServices => 'Editar servicios',
            self::UploadFile => 'Cargar archivos',
            self::DeleteServices => 'Eliminar servicios',
            self::PurgeServices => 'Eliminar servicios definitivamente',
        };
    }
}
