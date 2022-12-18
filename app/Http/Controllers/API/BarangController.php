<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Barang;
Use Exception;

class BarangController extends Controller
{
    private $response = array('message' => '', 'error' => 1, 'data' => []);

    public function index()
    {
        $this->response['data'] = Barang::all();
        $this->response['error'] = 0;

        return response()->json($this->response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:barang',
            'harga' => 'required|numeric|between:0,999999999999.99',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $input = $request->all();

        try {
            $store = Barang::create($input);

            if($store){
                $this->response['error'] = 0;
                $this->response['message'] = 'Success Store';
            }else{
                $this->response['message'] = 'Failed Store';
            }

            return response()->json($this->response);

        } catch(Exception $e) {
            $this->response['message'] = $e->getMessage();
            return response()->json($this->response);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:barang',
            'harga' => 'required|numeric|between:0,999999999999.99',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $input = $request->all();

        try {
            $update = Barang::where('id',$id)->update($input);

            if($update){
                $this->response['error'] = 0;
                $this->response['message'] = 'Success Update';
            }else{
                $this->response['message'] = 'Failed Update';
            }

            return response()->json($this->response);

        } catch(Exception $e) {
            $this->response['message'] = $e->getMessage();
            return response()->json($this->response);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = Barang::where('id',$id)->delete();

            if($delete){
                $this->response['error'] = 0;
                $this->response['message'] = 'Success Delete';
            }else{
                $this->response['message'] = 'Failed Delete';
            }

            return response()->json($this->response);

        } catch(Exception $e) {
            $this->response['message'] = $e->getMessage();
            return response()->json($this->response);
        }
    }
}
