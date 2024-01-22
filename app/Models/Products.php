<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;

class Products extends Model
{
    use HasFactory, Sortable;

    public function company()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    protected $fillable = [
        'product_name',
        'company_id',
        'price',
        'stock',
        'comment',
        'img_path'
    ];

    protected $attributes = [
        'img_path' => '商品画像.jpg', // デフォルトの画像ファイル名を設定
        'comment' => ''
    ];


    public function sales()
    {
        return $this->hasMany(Sales::class);
    }


    public static function storeProduct($request)
    {
        $image = $request->file('img_path');
        $path = null;

        if ($request->hasFile('img_path')) {
            $path = \Storage::put('/public', $image);
            $path = explode('/', $path);
        }

        return self::create([
            'product_name' => $request->input('product_name'),
            'company_id' => $request->input('company_id'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'comment' => $request->input('comment'),
            'img_path' => $path ? $path[1] : 'default_image_path',
        ]);
    }

    public static function updateProduct($request, $id)
    {
        $product = self::find($id);
        $product->fill($request->all());

        // 画像がアップロードされている場合の処理
        if ($request->hasFile('img_path')) {

            // 新しい画像を保存
            $path = $request->file('img_path')->store('images', 'public');
            $product->img_path = $path;
        }

        $product->save();

        return $product;
    }
}
