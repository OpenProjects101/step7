<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
       @vite(['resources/css/app.css'])
       <script src="{{ asset('js/confirmDelete.js') }}" defer></script>
      <title>商品一覧画面</title>
  </head>
    
  <body>
    @extends('layouts.app')
    @section('content')
    <div class="container ProductList">
      <h1>商品一覧画面</h1>

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

          <form id="searchForm" class="search">
              <div class="search product">
                <input type="text" id="search" name="search" class="form" placeholder="検索キーワード" value="{{ request('search') }}">
              </div>

              <div class="search company">
                <select name="company_id" id="company_id">
                  <option value="" >メーカー名</option>
                  @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="search price-range">
                  
              
                  <input type="text" id="price_max" name="price_max" placeholder="価格の上限" value="{{ request('price_max') }}">
                  <input type="text" id="price_min" name="price_min" placeholder="価格の下限" value="{{ request('price_min') }}">
              </div>


              <div class="search stock-range">
                  <input type="text" id="stock_min" name="stock_min" placeholder="在庫数の下限" value="{{ request('stock_min') }}">
                  <input type="text" id="stock_max" name="stock_max" placeholder="在庫数の上限" value="{{ request('stock_max') }}">
              </div>

              <div class="btn search">
                <button class="btn btn-outline-secondary"  type="submit" id="searchButton">検索</button>
              </div>


              <script>
                $(document).ready(function() {
                    let csrfToken = '{{ csrf_token() }}'; 

                    //$('#searchButton').on('click', function() {
                      $('#searchButton').on('click', function(event) {
                      event.preventDefault();

                        let searchQuery = $('#search').val(); 
                        let companyId = $('#company_id').val(); 
                        let priceMin = $('#price_min').val();
                        let priceMax = $('#price_max').val();
                        let stockMin = $('#stock_min').val();
                        let stockMax = $('#stock_max').val();


                        $.ajax({
                            url: '{{ route('products.index') }}', 
                            method: 'GET', 
                            dataType: "json",
                            data: {
                                search: searchQuery,
                                company_id: companyId,
                                price_min: priceMin,
                                price_max: priceMax,
                                stock_min: stockMin,
                                stock_max: stockMax,
                            },
                            success: function(data) {
                                $('#results').empty(); 
                                data.products.forEach(function(product) {
                                    $('#results').append(`
                                        <tr>
                                            <td>${product.id}</td>
                                            <td><img src="${product.img_path}" alt="商品画像" width="100"></td>
                                            <td>${product.product_name}</td>
                                            <td>${product.price}</td>
                                            <td>${product.stock}</td>
                                            <td>${product.company_name}</td>
                                            <td>
                                            <button type="button" onclick="location.href='{{ url('/products') }}/${product.id}'" class="btn detail">詳細</button>   
                                            <form method="POST" action="/products/${product.id}" class="d-inline" onsubmit="return confirmDelete();">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn delete">削除</button>
                                                </form>
                                            </td>
                                        </tr>
                                    `);
                                });
                             },
                           })
                        });
                    });

                $(document).ready(function() {
                  $('#productTable th').on('click', function() {
                      let columnIndex = $(this).index();
                      let columnMap = ['id', 'img_path', 'product_name', 'price', 'stock', 'company_name']; 
                      let column = columnMap[columnIndex] || 'id'; 
                      let order = $(this).attr('data-order') || 'asc';
                      let newOrder = (order === 'asc') ? 'desc' : 'asc';
                      $(this).attr('data-order', newOrder);

                      $.ajax({
                          url: '{{ route('products.index') }}', 
                          method: 'GET',
                          data: {
                              sort_column: column,
                              sort_order: newOrder,
                              search: $('#search').val(), 
                              company_id: $('#company_id').val(),
                              price_min: $('#price_min').val(),
                              price_max: $('#price_max').val(),
                              stock_min: $('#stock_min').val(),
                              stock_max: $('#stock_max').val(),
                          },
                          success: function(data) {
                              $('#results').empty();
                              data.products.forEach(function(product) {
                                  $('#results').append(`
                                      <tr>
                                          <td>${product.id}</td>
                                          <td><img src="${product.img_path}" alt="商品画像" width="100"></td>
                                          <td>${product.product_name}</td>
                                          <td>${product.price}</td>
                                          <td>${product.stock}</td>
                                          <td>${product.company.company_name}</td>
                                          <td>
                                                <button type="button" onclick="location.href='{{ url('/products') }}/${product.id}'" class="btn detail">詳細</button>
                                                <form method="POST" action="/products/${product.id}" class="d-inline" onsubmit="return confirmDelete();">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn delete">削除</button>
                                                </form>
                                            </td>
                                      </tr>
                                  `);
                              });
                          },
                          error: function(xhr) {
                              console.error('エラーが発生しました:', xhr.responseText);
                              }
                          });
                      });
                  });

                $(document).ready(function() {
                  if ($('#productTable').length) {
                      $("#productTable").tablesorter({
                          headers: {
                              1: { sorter: false }, 
                              6: { sorter: false },
                              }
                          });
                      }
                  });

                $(document).on('submit', '.d-inline', function(event) {
                  event.preventDefault();

                  let form = $(this);
                  let actionUrl = form.attr('action');

                  $.ajax({
                      url: actionUrl,
                      method: 'POST',
                      data: form.serialize(), 
                      dataType: 'json', 
                      success: function() {
                          form.closest('tr').remove();
                          alert('削除が完了しました');
                      },
                      error: function() {
                          //console.error('削除に失敗しました:', xhr.responseText);
                          alert('削除に失敗しました');
                          }
                      });
                  });
            </script>


         </form>

          <table id="productTable" class="ProductList" border="1">
            <thead>
                <tr>
                  <th data-column="id" data-order="asc">ID</th>
                  <th class="sorter-false">商品画像</th>
                  <th data-column="product_name" data-order="asc">商品名</th>
                  <th data-column="price" data-order="asc">価格</th>
                  <th data-column="stock" data-order="asc">在庫数</th>
                  <th data-column="company" data-order="asc">メーカー名</th>
                  <th class="sorter-false">
                    <button type="button" onclick="location.href='{{ route('products.create') }}'" class="btn registerpage">新規登録</button>
                  </th>
                </tr>
            </thead>

            <tbody id="results">
                @foreach ($products as $product)
                  <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->img_path)
                            <img src="{{ asset($product->img_path) }}" alt="商品画像" width="100">
                        @endif
                    </td>

                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                      <button type="button" onclick="location.href='{{ route('products.show', $product) }}'" class="btn detail">詳細</button>
                      <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline" onsubmit="return confirmDelete();">
                            @csrf
                              @method('DELETE')
                            <button type="submit" class="btn delete">削除</button>
                      </form>
                      </td>
                  </tr>
                @endforeach
            </tbody>
          </table>
          {{-- {{ $products->appends(request()->query())->links() }} --}}


    @endsection

    
    </div>  
  </body>
</html> 