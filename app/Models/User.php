<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserStatus;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    //ép kiểu các thuộc tính để có thể gán giá trị hàng loạt
    //chuyển đổi status từ string sang enum
     protected $casts = [
        'status' => UserStatus::class,
    ];

    //xác định các thuộc tính có thể gán giá trị hàng loạt, giúp chống lại tấn công gán giá trị hàng loạt
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'status',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

     //danh sách các thuộc tính sẽ được ẩn khi chuyển đổi thành mảng hoặc JSON
     protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class, // Chuyển đổi status từ string sang enum
        ];
    }

    /**
     * Accessor for the name attribute.
     *
     * @return string
     */
    
    //Dùng Accessor để tạo thuộc tính ảo
     public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
//     protected function address(): Attribute
// {
//     return Attribute::make(
//         get: fn (mixed $value, array $attributes) => new Address(
//             $attributes['address_line_one'],
//             $attributes['address_line_two'],
//         ),
//     );
//}
}
