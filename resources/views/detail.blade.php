<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>詳細画面</title>

</head>

<body>
    <h1>詳細画面</h1>

    <table>
        <tr>
            <th>ID</th>
            <td>{{$products->id}}</td>
        </tr>
        <tr>
            <th>商品画像</th>
            <td><img src="{{ Storage::url($products->img_path)}}" alt=""></td>
        </tr>
        <tr>
            <th>商品名</th>
            <td>{{$products->product_name}}</td>
        </tr>
        <tr>
            <th>メーカー</th>
            <td>{{$products->company->company_name}}</td>
        </tr>
        <tr>
            <th>価格</th>
            <td>{{$products->price}}円</td>
        </tr>
        <tr>
            <th>在庫数</th>
            <td>{{$products->stock}}</td>
        </tr>
        <tr>
            <th>コメント</th>
            <td>{{$products->comment}}</td>
        </tr>
    </table>
    <a href="{{ route('products.edit', ['product' => $products->id]) }}">編集</a>
    <a href="{{ route('products.index')}}">戻る</a>
</body>

</html>


<!-- $product->id -->