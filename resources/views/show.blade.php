<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/app.css'])
        <title>商品情報詳細画面</title>
    </head>

    <body>
        @extends('layouts.app')
        @section('content')
            <div class="container detail">
                <h1>商品情報詳細画面</h1>
                    <div class="table detail" >
                        <dt class="col-sm-3">ID</dt>
                        <dd class="col-sm-9">{{ $product->id }}</dd>

                        <dt class="col-sm-3">商品画像</dt>
                        <dd>
                        @if($product->img_path)
                            <img src="{{ $product->img_path }}">
                        @else
                            <p>画像がありません</p>
                        @endif
                        </dd>

                        <dt class="col-sm-3">商品名</dt>
                        <dd class="col-sm-9">{{ $product->product_name }}</dd>

                        <dt class="col-sm-3">メーカー</dt>
                        <dd class="col-sm-9">{{ $product->company->company_name }}</dd>

                        <dt class="col-sm-3">価格</dt>
                        <dd class="col-sm-9">{{ $product->price }}</dd>

                        <dt class="col-sm-3">在庫数</dt>
                        <dd class="col-sm-9">{{ $product->stock }}</dd>

                        <dt class="col-sm-3">コメント</dt>
                        <dd class="col-sm-9">{{ $product->comment }}</dd>

                        <a href="{{ route('products.edit', $product) }}" class="btn edit">編集</a>
                        <a href="{{ route('products.index') }}" class="detail-btn back">戻る</a>
                    </div>
            </div>
        @endsection 
    </body>
</html>