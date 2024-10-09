<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
       @vite(['resources/css/app.css'])
      <title>商品新規登録画面</title>
  </head>
    
  <body>
    @extends('layouts.app')

    @section('content')
    <div class="container create">
        <h1>商品新規登録画面</h1>
            @if (session('success'))
                    <div class="alert success">
                        {{ session('success') }}
                    </div>
            @endif

            @if($errors->any())
                <div class="alert success">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <div class="table create">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="create-group">
                        <dt for="product_name" class="form-label">商品名<span class="required"> *</span></dt>
                        <dd><input id="product_name" type="text" name="product_name" class="form-control"></dd>
                    </div>

                    <div class="create-group">
                        <dt for="company_id" class="form-label">メーカー名<span class="required"> *</span></dt>
                        <dd><select class="form-select" id="company_id" name="company_id">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select></dd>
                    </div>

                    <div class="create-group">
                        <dt for="price" class="form-label">価格<span class="required"> *</span></dt>
                        <dd><input id="price" type="text" name="price" class="form-control"></dd>
                    </div>

                    <div class="create-group">
                        <dt for="stock" class="form-label">在庫数<span class="required"> *</span></dt>
                        <dd><input id="stock" type="text" name="stock" class="form-control"></dd>
                    </div>

                    <div class="create-group">
                        <dt for="comment" class="form-label">コメント</dt>
                        <dd><textarea id="comment" name="comment" class="form-control" rows="3"></textarea></dd>
                    </div>

                    <div class="create-group">
                        <dt for="img_path" class="form-label">商品画像</dt>
                        <dd><input id="img_path" type="file" name="img_path" class="form-control"></dd>
                    </div>

                    <button type="submit" class="btn register">新規登録</button>
                    <a href="{{ route('products.index') }}" class="create-btn back">戻る</a>
                </form>
            </div>
        </div>
    @endsection
  </body>
</html>