<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\company;

class ProductController extends Controller {
    
    public function index(Request $request) {

        $request->validate([
            'search' => ['nullable','regex:/^[\p{L}\p{N}]+$/u',],
        ], [
            'search.regex' => '半角または全角の文字および数字で検索してください。',
        ]);

        $companies = Company::all();
        $query = Product::with ('company');

        if ($search = $request->search) {
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        if ($companyId = $request->input('company_id')) {
            $query->where('company_id', $companyId);
        }

        $products = $query->paginate(10);

        return view('index', compact('products', 'companies', 'search','companyId'));
    }


    public function create() {
        try {
            $companies = Company::all();
        } catch (\Exception $e) {
            Log::error('情報の取得に失敗しました: ' . $e->getMessage());
            return redirect()->back()->with('error', '情報の取得に失敗しました。');
        }

        return view('create', compact('companies'));
    }

    public function store(Request $request) {
        $request->validate([
            'product_name' => ['required','regex:/^[\p{L}\p{N}]+$/u'],
            'company_id' =>  ['required'],
            'price' =>  ['required','regex:/^[a-zA-Z0-9]+$/'],
            'stock' =>  ['required','regex:/^[a-zA-Z0-9]+$/'],
            'comment' =>  ['nullable','regex:/^[\p{L}\p{N}]+$/u',], 
            'img_path' => ['nullable','image'],
        ], [
            'product_name.required' => '商品名は必須です。',
            'company_id.required' => 'メーカー名は必須です。',
            'price.required' => '価格は必須です。',
            'stock.required' => '在庫数は必須です。',
            'product_name.regex' => '商品名は半角英数字・全角英数字のみを使用してください。',
            'price.regex' => '価格は半角英数字のみを使用してください。',
            'stock.regex' => '在庫数は半角英数字のみを使用してください。',
            'comment.regex' => 'コメントは半角英数字・全角英数字のみを使用してください。',

        ]);

        $product = new Product([
            'product_name' => $request->get('product_name'),
            'company_id' => $request->get('company_id'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'comment' => $request->get('comment'),
        ]);

        if($request->hasFile('img_path')){ 
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            $product->img_path = '/storage/' . $filePath;
        }

        $product->save();

        return redirect()->route('products.create')->with('success', '商品が登録されました');
    }

    public function show(Product $product) {
         return view('show', compact('product'));
    }

    public function edit(Product $product) {
        $companies = Company::all();
        return view('edit', compact('product', 'companies'));
    }

    public function update(Request $request, Product $product) {
        $request->validate([
            'product_name' => ['required','regex:/^[\p{L}\p{N}]+$/u'],
            'company_id'=> ['required'],
            'price' => ['required','regex:/^[a-zA-Z0-9]+$/'],
            'stock' => ['required','regex:/^[a-zA-Z0-9]+$/'],
            'comment' => ['nullable','regex:/^[\p{L}\p{N}]+$/u'],
            'img_path' =>['nullable','image'],
        ], [
            'product_name.required' => '商品名は必須です。',
            'company_id.required' => 'メーカー名は必須です。',
            'price.required' => '価格は必須です。',
            'stock.required' => '在庫数は必須です。',
            'product_name.regex' => '商品名は半角英数字・全角英数字のみを使用してください。',
            'price.regex' => '価格は半角英数字のみを使用してください。',
            'stock.regex' => '在庫数は半角英数字のみを使用してください。',
            'comment.regex' => 'コメントは半角英数字・全角英数字のみを使用してください。',
        ]);

        try {
            $product->product_name = $request->product_name;
            $product->company_id = $request->company_id;
            $product->price = $request->price;
            $product->stock = $request->stock;

            $product->save();

            return redirect()->route('products.edit', $product->id)
            ->with('success', '商品情報が更新されました');
        } catch (\Exception $e) {
            Log::error('商品情報の更新に失敗しました: ' . $e->getMessage());

            return redirect()->back()->with('error', '商品情報の更新に失敗しました。');
         }
    }  

    public function destroy($id) {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return redirect('/products')->with('success', '商品が削除されました');
        } catch (\Exception $e) {
        Log::error('商品の削除に失敗しました: ' . $e->getMessage());

        return redirect('/products')->with('error', '商品の削除に失敗しました。');
        }
    }
}