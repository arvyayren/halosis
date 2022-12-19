<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Barang;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    
    protected $fillable = [
        'user_id', 'status', 'total','nama_customer','alamat'
    ];

    public static function updateTotal($id){
        $cart = Cart::find($id);

        $total = 0;
        foreach($cart->detail as $detail){
            $barang = Barang::where('kode',$detail['kode_barang'])->first();
            if(isset($barang)){
                $total = $total+($barang->harga*$detail['qty']);
            }else{
                $response = array('data' => [], 'error' => 1, 'message' => 'Failed update total cart');
                return $response;
            }
        }

        $cart->total = $total;

        if($cart->save()){
            $response = array('data' => $cart, 'error' => 0, 'message' => 'Success update total cart');
            return $response;
        }else{
            $response = array('data' => [], 'error' => 1, 'message' => 'Failed update total cart');
            return $response;
        }
    }

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function detail() {
        return $this->hasMany('App\Models\CartDetail', 'cart_id');
    }
}
