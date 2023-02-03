<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Staff extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'gender',
    ];

    public function user() {
        return $this->hasOne(User::class);
    }
}
