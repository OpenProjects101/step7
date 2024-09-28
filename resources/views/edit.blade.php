<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/app.css'])
        <title>商品情報編集画面</title>
    </head>
    <body>
        @extends('layouts.app')

        @section('content')
        <div class="container edit">
            <h1>商品情報編集画面</h1>

                @if (session('success'))
                        <div class="alert success">
                            {{ session('success') }}
                        </div>
                @endif

                <div class="table edit">
                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                            <div class="form-group">
                                <dt for="product_name" class="form-label">商品名<span class="required"> *</span></dt>
                                <dd><input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required></dd>
                            </div>

                            <div class="form-group">
                                <dt for="company_id" class="form-label">メーカー名<span class="required"> *</span></dt>
                                <dd><select class="form-select" id="company_id" name="company_id">
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                                    @endforeach
                                </select></dd>
                            </div>

                            <div class="form-group">
                                <dt for="price" class="form-label">価格<span class="required"> *</span></dt>
                                <dd><input type="text" class="form-control" id="price" name="price" value="{{ $product->price }}" required></dd>
                            </div>

                            <div class="form-group">
                                <dt for="stock" class="form-label">在庫数<span class="required"> *</span></dt>
                                <dd><input type="text" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required></dd>
                            </div>

                            <div class="form-group">
                                <dt for="comment" class="form-label">コメント</dt>
                                <dd><textarea id="comment" name="comment" class="form-control" rows="3">{{ $product->comment }}</textarea></dd>
                            </div>

                            <div class="form-group">
                                <dt for="img_path" class="form-label">商品画像</dt>
                                <dd><input id="img_path" type="file" name="img_path" class="form-control"></dd>
                            </div>
                    <button type="submit" class="btn update">更新</button>
                    <a href="{{ route('products.show', $product->id) }}" class="edit-btn back">戻る</a>
                </div>
            @endsection
        </div>   
    </body>
</html>