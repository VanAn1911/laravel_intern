<?php
namespace App\Enums;

enum UserStatus: int
{
    case PENDING = 0;   // Chờ phê duyệt
    case APPROVED = 1;  // Được phê duyệt
    case REJECTED = 2;  // Bị từ chối
    case BLOCKED = 3;   // Bị khoá

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ phê duyệt',
            self::APPROVED => 'Đã phê duyệt',
            self::REJECTED => 'Từ chối',
            self::BLOCKED => 'Bị khóa',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'secondary',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::BLOCKED => 'dark',
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'color' => $this->color(),
        ];
    }
}