<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    // Hàm hiển thị nhãn
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Quản trị viên',
            self::USER => 'Người dùng',
        };
    }
}
