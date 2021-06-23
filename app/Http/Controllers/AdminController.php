<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    public function index()
    {
        $title = "Товары";
        $products = Product::all();
        return view('admin', ['title' => $title, 'products' => $products]);
    }
}
