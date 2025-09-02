<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddCartController extends Controller
{
     public function addcart(){
        return view('website.pages.cart');
    }
}