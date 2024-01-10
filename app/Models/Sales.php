<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    //$fillableの設定はする？確認して記載するか決める。

    protected $fillable = [

        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
