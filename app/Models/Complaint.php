<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Status;


class Complaint extends Model
{
    protected $casts = [
        'status' => Status::class,
    ];

    protected $fillable = [
    'title',    
    'description',
    'photo',
    'user_id',
    'category_id',
    'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    
}
