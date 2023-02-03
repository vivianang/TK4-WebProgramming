<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\TransactionDetail;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'transactions';

    protected $fillable = [
        'customer_id',
        'total',
        'status',
        'approved_at'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function details() {
        return $this->hasMany(TransactionDetail::class);
    }
}
