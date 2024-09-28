<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductlistController extends Controller
{
    public function productlist()  
     {
        $products = Product::all(); 
        return view('productlist', ['products' => $products ]);
     }

     public function detail(Product $product)
    {
        return view('detail', ['product' => $product]);
    }

    public function edit(Product $product)
    {
        // 商品編集画面で会社の情報が必要なので、全ての会社の情報を取得します。
        $companies = Company::all();
        // 商品編集画面を表示します。その際に、商品の情報と会社の情報を画面に渡します。
        return view('edit', compact('product', 'companies'));
    }

}

