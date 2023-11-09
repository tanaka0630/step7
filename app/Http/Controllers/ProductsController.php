<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Companies::all();

        $keyword = $request->input('keyword');
        $companyName = $request->input('company_name');

        $query = Products::query();

        if (!empty($keyword)) {
            $query->where('product_name', 'like', '%' . $keyword . '%');
        }

        if (!empty($companyName)) {
            $query->where('company_id', $companyName);
        }

        $products = $query->get();

        // 検索結果をビューに渡す
        return view('products', compact('products', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 会社名のデータを取得（適切な方法でデータを取得してください）
        $companies = Companies::all(); // 例: Company モデルを使って会社名データを取得する

        return view('create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable',
            'img_path' => 'nullable|image',
            // 他のフィールドに関するバリデーションルールを追加
        ]);

        // 'comment' フィールドが送信されていない場合、デフォルトの値を設定
        if (!isset($data['comment'])) {
            $request->merge(['comment' => 'デフォルトコメント']); // デフォルトのコメントを設定
        }

        // フォームデータから新しい商品を作成
        $product = Products::create($request->all());
        // $company = Companies::all();


        return redirect()->route('products.index')
            ->with('success', '商品が新規登録されました');
        // return redirect()->route('products.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Products::find($id);
        return view('detail', compact('product'))->with('products', $product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $companies = Companies::all();
        $product = Products::find($id);
        // dd($company);
        return view('edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // データベースを更新
        $product = Products::find($id);
        $product->fill($request->all());
        $product->save();

        $company = Companies::find($request->input('company_id')); // メーカー情報を取得
        $company->fill($request->all());
        $company->save();

        // 更新が成功したらリダイレクト
        return redirect()->route('products.index')
            ->with('success', '商品情報が更新されました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);

        if ($product) {
            $product->delete();
            return redirect()->route('products.index')->with('success', '商品が削除されました');
        } else {
            return redirect()->route('products.index')->with('error', '商品が見つかりません');
        }
    }
}
