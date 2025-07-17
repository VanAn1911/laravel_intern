<?php
namespace App\Enums;

enum PostStatus: int
{
    case NEW = 0;
    case APPROVED = 1;
    case REJECTED = 2;

    public function label(): string
    {
        return match($this) {
            self::NEW => 'Bài mới',
            self::APPROVED => 'Đã phê duyệt',
            self::REJECTED => 'Từ chối',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'secondary',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
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