<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Barang;
use App\Models\Cart;
use App\Models\CartDetail;
Use Exception;

use Auth;

class CartController extends Controller
{
    private $response = array('message' => '', 'error' => 1, 'data' => []);

    public function index()
    {
        $user = User::getUser()['data']['user'];
        $cart = Cart::where(['user_id' => $user->id, 'status' => 'cart'])->first();

        if(isset($cart)){
            if(count($cart->detail) < 1){
                $this->response['message'] = 'Data Cart is Empty';
                $this->response['error'] = 1;
            }else{
                $this->response['data'] = $cart;
                $this->response['error'] = 0;
            }
        }else{
            $this->response['message'] = 'Data Cart is Empty';
            $this->response['error'] = 1;
        }
        
        return response()->json($this->response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|string|max:255',
            'qty' => 'required|numeric|min:1',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $input = $request->all();
        $user = User::getUser()['data']['user'];
        $barang = Barang::where('kode',$input['kode_barang'])->first();

        if(!isset($barang)){
            $this->response['message'] = 'Barang not found';
            return response()->json($this->response);
        }
        
        $cart = Cart::where(['user_id' => $user->id, 'status' => 'cart'])->first();

        if(isset($cart)){
            $cart_detail = CartDetail::where(['cart_id' => $cart->id,'kode_barang' => $input['kode_barang']])->first();

            if(isset($cart_detail)){
                $cart_detail->qty = $cart_detail->qty+$input['qty'];

                if($cart_detail->save()){
                    $update_total = Cart::updateTotal($cart->id);
                    if($update_total['error'] == 0){
                        $this->response['error'] = 0;
                        $this->response['message'] = 'Success add to cart';
                    }else{
                        $this->response['message'] = $update_total['message'];
                    }
                }else{
                    $this->response['message'] = 'Failed add to cart';
                }
            }else{
                try {
                    $input['cart_id'] = $cart->id;
                    $store_cart_detail = CartDetail::create($input);
        
                    if($store_cart_detail){
                        $update_total = Cart::updateTotal($cart->id);
                        
                        if($update_total['error'] == 0){
                            $this->response['error'] = 0;
                            $this->response['message'] = 'Success add to cart';
                        }else{
                            $this->response['message'] = $update_total['message'];
                        }
                    }else{
                        $this->response['message'] = 'Failed add to cart';
                    }
                } catch(Exception $e) {
                    $this->response['message'] = $e->getMessage();
                    return response()->json($this->response);
                }
            }
        }else{
            try {
                $total = $barang->harga*$input['qty'];
                $input_cart = array('user_id' => $user->id, 'status' => 'cart','total' => $total);
                $store_cart = Cart::create($input_cart);
    
                if($store_cart){
                    try {
                        $input['cart_id'] = $store_cart->id;
                        $store_cart_detail = CartDetail::create($input);
            
                        if($store_cart_detail){
                            $this->response['error'] = 0;
                            $this->response['message'] = 'Success add to cart';
                        }else{
                            $this->response['message'] = 'Failed add to cart';
                        }
                    } catch(Exception $e) {
                        $this->response['message'] = $e->getMessage();
                        return response()->json($this->response);
                    }
                }else{
                    $this->response['message'] = 'Failed add to cart';
                }
            } catch(Exception $e) {
                $this->response['message'] = $e->getMessage();
                return response()->json($this->response);
            }
        }

        return response()->json($this->response);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|string|max:255',
            'qty' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $input = $request->all();
        $user = User::getUser()['data']['user'];
        $barang = Barang::where('kode',$input['kode_barang'])->first();

        if(!isset($barang)){
            $this->response['message'] = 'Barang not found';
            return response()->json($this->response);
        }
        
        $cart = Cart::where(['user_id' => $user->id, 'status' => 'cart'])->first();

        if(isset($cart)){
            $cart_detail = CartDetail::where(['cart_id' => $cart->id,'kode_barang' => $input['kode_barang']])->first();

            if(isset($cart_detail)){
                if($input['qty'] == 0){
                    if($cart_detail->delete()){
                        $update_total = Cart::updateTotal($cart->id);
                        if($update_total['error'] == 0){
                            $this->response['error'] = 0;
                            $this->response['message'] = 'Success remove barang from cart';
                        }else{
                            $this->response['message'] = $update_total['message'];
                        }
                    }else{
                        $this->response['message'] = 'Failed update qty cart';
                    }
                }else{
                    $cart_detail->qty = $input['qty'];

                    if($cart_detail->save()){
                        $update_total = Cart::updateTotal($cart->id);
                        if($update_total['error'] == 0){
                            $this->response['error'] = 0;
                            $this->response['message'] = 'Success update qty cart';
                        }else{
                            $this->response['message'] = $update_total['message'];
                        }
                    }else{
                        $this->response['message'] = 'Failed update qty cart';
                    }
                }
            }else{
                $this->response['message'] = 'No barang found in cart';
            }
        }else{
            $this->response['message'] = 'No barang found in cart';
        }

        return response()->json($this->response);
    }

    public function empty()
    {
        try {
            $user = User::getUser()['data']['user'];
            
            $cart = Cart::where(['user_id' => $user->id, 'status' => 'cart'])->first();

            if(isset($cart)){
                if($cart->delete()){
                    $this->response['error'] = 0;
                    $this->response['message'] = 'Success empty cart';
                }else{
                    $this->response['message'] = 'Failed empty cart';
                }
            }else{
                $this->response['message'] = 'Cart is already empty';
            }
            
            return response()->json($this->response);

        } catch(Exception $e) {
            $this->response['message'] = $e->getMessage();
            return response()->json($this->response);
        }
    }

    public function checkout(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_customer' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $input = $request->all();
        $user = User::getUser()['data']['user'];
        
        $cart = Cart::where(['user_id' => $user->id, 'status' => 'cart'])->first();

        if(isset($cart) && $cart->total != 0){
            $cart->nama_customer = $input['nama_customer'];
            $cart->alamat = $input['alamat'];
            $cart->status = 'paid';

            if($cart->save()){
                $this->response['data'] = $cart;
                $this->response['data']['detail'] = $cart->detail;
                $this->response['error'] = 0;
                $this->response['message'] = 'Success checkout';
            }else{
                $this->response['message'] = 'Failed checkout';
            }
        }else{
            $this->response['message'] = 'Cart is empty';
        }

        return response()->json($this->response);
    }
}
