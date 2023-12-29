<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title>編集画面</title>
</head>

<body>
    <h1>編集画面</h1>
    <form action="{{ route('products.update', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method ('PUT')
        <div>
            <label for="">ID</label>
            <input type="hidden" value="{{$product->id}}" disabled>
        </div>
        <div>
            <label for="product_name">商品名</label>
            <input type="text" name="product_name" value="{{$product->product_name}}">
        </div>
        <div>
            <label for="company_id">メーカー名</label>
            <select name="company_id" id="company_id">
               
                @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }} > 
                    {{ $company->company_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="">価格</label>
            <input type="number" name="price" value="{{$product->price}}">
        </div>
        <div>
            <label for="">在庫数</label>
            <input type="number" name="stock" value="{{$product->stock}}">
        </div>
        <div>
            <label for="">コメント</label>
            <textarea name="comment" id="" cols="40" rows="5">{{$product->comment}}</textarea>
        </div>
        <div>
            <label for="">商品画像</label>
            <input type="file" name="img_path" value="{{$product->img_path}}">
        </div>
        <div>
            <input type="submit" value="更新">
            <a href="{{ route('products.index')}}">戻る</a>
        </div>
    </form>

</body>

</html>