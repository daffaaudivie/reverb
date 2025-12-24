<?php

namespace App\Enums;

enum Status: String
{
    case pending = 'pending';
    case diajukan = 'diajukan';
    case diproses = 'diproses';
    case selesai = 'selesai';
    case ditolak = 'ditolak';

    public function label(): string
    {
        return match($this) {
            self::pending => 'Pending',
            self::diajukan => 'Diajukan',
            self::diproses => 'Diproses',
            self::selesai => 'Selesai',
            self::ditolak => 'Ditolak',
        };
    }
}
