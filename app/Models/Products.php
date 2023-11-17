<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Products extends Model
{
    use HasFactory;

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
        'img_path' => '商品画像', // デフォルトの画像ファイル名を設定
        'comment' => ''
    ];


    public function registProducts($data)
    {

        DB::table('products')->insert([
            'product_name' => $data->product_name,
            'company_name' => $data->id,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'img_path' => $data->img_path
        ]);
    }
}
