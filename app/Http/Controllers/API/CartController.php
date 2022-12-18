<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\Cart;
use App\Models\CartDetail;
Use Exception;

use Auth;

class CartController extends Controller
{
    private $response = array('message' => '', 'error' => 1, 'data' => []);

    public function index() {
        $user = User::getUser()['data']['user'];
        $cart = Cart::where(['user_id' => $user->id, 'status' => 'cart'])->first();

        if(isset($cart)){
            if(empty($cart->detail())){
                $this->response['message'] = 'Data Cart is Empty';
                $this->response['error'] = 0;
            }else{
                $this->response['data'] = $cart->detail();
                $this->response['error'] = 0;
            }
        }else{
            $this->response['message'] = 'Data Cart is Empty';
            $this->response['error'] = 0;
        }
        
        return response()->json($this->response);
    }

    public function cartAuth() {
        $data = "Welcome " . Auth::user()->name;
        return response()->json($data, 200);
    }
}
