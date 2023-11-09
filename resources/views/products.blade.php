<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>商品一覧画面</title>
</head>

<body>
    <h1 class="test">商品一覧画面</h1>

    <a class="btn_create" href="{{route('products.create')}}">新規登録</a>
    <div class="search">
        <form action="{{route('products.index')}}" method="GET">
            @csrf
            <input type="text" name="keyword" placeholder="検索キーワード" value="{{ request('keyword') }}">


            <select name="company_name" id="">
                <option value="">メーカー名</option>

                @foreach ($companies as $company)
                <!-- <option value="{{ $company->id }}">{{ $company->company_name }}</option> -->
                <option value="{{ $company->id }}" {{ (request('company_name') == $company->id) ? 'selected' : '' }}>
                    {{ $company->company_name }}</option>
                @endforeach
                <!-- apache_child_terminate  -->
            </select>


            <input type="submit" value="検索">

        </form>

    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫</th>
            <th>メーカー名</th>
        </tr>
        @foreach ($products as $products)
        <tr>
            <td>{{$products->id}}</td>
            <td>{{$products->img_path}}</td>
            <td>{{$products->product_name}}</td>
            <td>{{$products->price}}円</td>
            <td>{{$products->stock}}</td>
            <td>{{$products->company->company_name}}</td>
            <td>
                <a href="{{ route('products.show', ['product' => $products->id]) }}">詳細</a>
            </td>
            <td>
                <form action="{{ route('products.destroy', ['product' => $products->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick='return confirm ("本当に削除しますか?")' >削除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

</body>

</html>