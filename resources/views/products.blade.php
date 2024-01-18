<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"
        integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <script src="{{ asset('js/ajaxFunction.js') }}"></script>
    <script>
        var searchUrl = "{{ route('search') }}";
        var assetPath = "{{ asset('storage/') }}";
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <title>商品一覧画面</title>
</head>

<body>
    <h1 class="test">商品一覧画面</h1>

    <a class="btn_create" href="{{ route('products.create') }}">新規登録</a>
    <div class="search">
        <form id="search_form" action="{{ url('/products') }}" method="GET">
            @csrf
            <div>
                <input id="keyword" type="text" name="keyword" placeholder="検索キーワード"
                    value="{{ request('keyword') }}">

                <select name="company_name" id="company_name">
                    <option value="">メーカー名</option>

                    @foreach ($companies as $company)
                        <!-- <option value="{{ $company->id }}">{{ $company->company_name }}</option> -->
                        <option value="{{ $company->id }}"
                            {{ request('company_name') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                    <!-- apache_child_terminate  -->
                </select>
            </div>
            <div>
                <input id="price_upper" placeholder="価格上限" type="number" name="price_upper"
                    value="{{ request('price_upper') }}">
                <input id="price_lower" placeholder="価格下限" type="number" name="price_lower"
                    value="{{ request('price_lower') }}">
                <input id="stock_upper" placeholder="在庫上限" type="number" name="stock_upper"
                    value="{{ request('stock_upper') }}">
                <input id="stock_lower" placeholder="在庫下限" type="number" name="stock_lower"
                    value="{{ request('stock_lower') }}">
            </div>

            <input id="search_btn" type="submit" value="検索">

        </form>

    </div>

    <table>
        <thead>

            <tr>
                <th id="sort_id" class="sortable" onclick="handleSortClick('id')">@sortablelink('id', 'ID')</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th id="sort_price" class="sortable" onclick="handleSortClick('price')">@sortablelink('price', '価格')</th>
                <th id="sort_stock" class="sortable" onclick="handleSortClick('stock')">@sortablelink('stock', '在庫')</th>
                <th>メーカー名</th>
            </tr>
        </thead>
        <tbody id="result_table">
            @foreach ($products as $products)
                <tr>
                    <td>{{ $products->id }}</td>
                    <td><img src="{{ asset('storage/' . $products->img_path) }}" alt=""></td>
                    <td>{{ $products->product_name }}</td>
                    <td>{{ $products->price }}円</td>
                    <td>{{ $products->stock }}</td>
                    <td>{{ $products->company->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', ['product' => $products->id]) }}">詳細</a>

                    </td>

                    <td>
                        <form id="deleteForm-{{ $products->id }}"
                            action="{{ route('products.destroy', ['product' => $products->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" data-id="{{ $products->id }}">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
