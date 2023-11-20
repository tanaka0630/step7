<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title></title>
</head>

<body>
    <h1>新規登録画面</h1>
    <div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="product_name">商品名</label>
                <input type="text" name="product_name" required>
                @error('product_name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="company_id">メーカー名</label>
                <select name="company_id" id="company_id">
                    <option value="">選択してください</option>
                    @foreach ($companies as $company)
                    <option value="{{ $company->id }}" id="" company_id>{{ $company->company_name }}</option>
                    @endforeach
                </select>

                @error('company_id')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            </div>
            <div>
                <label for="">価格</label>
                <input type="number" name="price">

                @error('price')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            </div>
            <div>
                <label for="">在庫数</label>
                <input type="number" name="stock">

                @error('stock')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            </div>
            <div>
                <label for="">コメント</label>
                <textarea name="comment" id="" cols="40" rows="5"></textarea>
            </div>
            <div>
                <label for="">商品画像</label>
                <input type="file" name="img_path">
            </div>


            <div>
                <input type="submit" value="新規登録">
                <a href="{{ route('products.index')}}">戻る</a>
            </div>
        </form>
    </div>
</body>

</html>