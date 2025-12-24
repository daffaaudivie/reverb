<?php
namespace App\Enums;

enum UserRole: int
{
    case ADMIN = 2;
    case USER = 1;
    
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'User',
        };
    }
}