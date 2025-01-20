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

        $query = Product::with('company');

        if ($search = $request->input('search')) {
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        if ($companyId = $request->input('company_id')) {
            $query->where('company_id', $companyId);
        }

        if ($priceMin = $request->input('price_min')) {
            $query->where('price', '>=', $priceMin);
        }
    
        if ($priceMax = $request->input('price_max')) {
            $query->where('price', '<=', $priceMax);
        }
    
        if ($stockMin = $request->input('stock_min')) {
            $query->where('stock', '>=', $stockMin);
        }
    
        if ($stockMax = $request->input('stock_max')) {
            $query->where('stock', '<=', $stockMax);
        }
        
        $sortColumn = $request->input('sort_column', 'id'); 
        $sortOrder = $request->input('sort_order', 'asc'); 
        $query->orderBy($sortColumn, $sortOrder);

        $query->leftJoin('companies', 'products.company_id', '=', 'companies.id')
              ->select('products.*', 'companies.company_name as company_name')
              ->orderBy($sortColumn, $sortOrder);

        $products = $query->get(); 
        

        if ($request->ajax()) {
            return response()->json(['products' => $products]);
        }

        $companies = Company::all();
        return view('index', compact('products', 'companies'));
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

            return response()->json(['success' => true, 'message' => '商品が削除されました。']);
        } catch (\Exception $e) {
          \Log::error('削除エラー: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => '削除に失敗しました。'], 500);
        }

    }
    
}