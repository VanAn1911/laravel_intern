<?php
namespace App\Enums;

enum UserStatus: int
{
    case Pending = 0;   // Chờ phê duyệt
    case Approved = 1;  // Được phê duyệt
    case Rejected = 2;  // Bị từ chối
    case Blocked = 3;   // Bị khoá
}