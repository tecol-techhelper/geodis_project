<?php

namespace App\Enums;

enum Permission: string
{
    case CreateUsers = 'create_users';
    case ViewUsers = 'view_users';
    case ViewUserDetails = 'view_user_details';
    case UpdateUsers = 'update_users';
    case UpdateUserStatus = 'update_user_status';
    case DeleteUsers= 'delete_users';
    case ViewServices = 'view_services';
    case EditTransportBlock = 'edit_transport_block';
    case EditAccountingBlock = 'edit_accounting_block';
    case EditTrackingBlock = 'edit_tracking_block';
    case EditPaymentBlock = 'edit_payment_block';
    case EditRecordBlock = 'edit_record_block';
    case UploadFile = 'uploadt_files';
    case DeleteServices = 'delete_services';

    public function label(): string
    {
        return match($this){
            self::CreateUsers => 'Crear Usuario',
            self::ViewUsers => 'Consultar usuarios',
            self::ViewUserDetails => 'Consultar usuario',
            self::UpdateUsers => 'Editar usuarios',
            self::UpdateUserStatus => 'Estados de estado de usuarios',
            self::DeleteUsers => 'Eliminar usuarios',
            self::ViewServices => 'Consultar servicios',
            self::EditTransportBlock => 'Editar bloque de transporte',
            self::EditAccountingBlock => 'Editar bloque de contabilidad',
            self::EditTrackingBlock => 'Editar bloque de seguimiento',
            self::EditPaymentBlock => 'Editar bloque de pago',
            self::EditRecordBlock => 'Editar bloque de acta',
            self::DeleteServices => 'Eliminar servicios',
        };
    }
}
