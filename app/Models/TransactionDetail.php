<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Item;
use App\Models\Transaction;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaction_details';

    protected $fillable = [
        'transaction_id',
        'item_id',
        'qty',
        'price',
        'total_price'
    ];

    public function transaction() {
        return $this->hasOne(Transaction::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
