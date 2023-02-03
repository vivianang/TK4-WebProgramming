<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'place_of_birth',
        'date_of_birth',
        'address',
        'identity_photo_url',
        'gender',
    ];

    public function user() {
        return $this->hasOne(User::class);
    }
}
