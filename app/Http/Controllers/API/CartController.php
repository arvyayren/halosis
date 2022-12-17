<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

class CartController extends Controller
{
    public function index() {
        $data = "Data List Cart";
        return response()->json($data, 200);
    }

    public function cartAuth() {
        $data = "Welcome " . Auth::user()->name;
        return response()->json($data, 200);
    }
}
