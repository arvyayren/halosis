<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    protected $table = 'cart_detail';
    
    protected $fillable = [
        'cart_id', 'kode_barang','qty'
    ];

    public function cart() {
        return $this->belongsTo('App\Models\Cart', 'cart_id');
    }
}
