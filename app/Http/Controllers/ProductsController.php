<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ItemNotFoundException;

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

        // 画像フォームでリクエストした画像を取得
        $image = $request->file('img_path');
        $dd = $image;

        // 画像情報がセットされていれば、保存処理を実行
        //  if (isset($image)) {
        //      // storage > public > img配下に画像が保存される
        //      $path = $image->store('image','public');
        //      // store処理が実行できたらDBに保存処理を実行
        //      if ($path) {
        //          // DBに登録する処理
        //          Products::create([
        //              'img_path' => $path,
        //          ]);
        //      }
        //  }

        if ($request->hasFile('img_path')) {
            $path = \Storage::put('/public', $image);
            $path = explode('/', $path);
        } else {
            $path = null;
        }

        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable',
            'img_path' => 'nullable|image',
        ]);


        // フォームデータから新しい商品を作成
        $product = Products::create([
            'product_name' => $request->input('product_name'),
            'company_id' => $request->input('company_id'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'comment' => $request->input('comment'),
            'img_path' => $path ? $path[1] : 'default_image_path', // デフォルトの画像パスを指定

        ]);

        return redirect()->route('products.index')
            ->with('success', '商品が新規登録されました');
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


        // 画像がアップロードされている場合の処理
        if ($request->hasFile('img_path')) {
            // 以前の画像を削除する（オプション）
            // Storage::delete($product->img_path);

            // 新しい画像を保存
            $path = $request->file('img_path')->store('images', 'public');
            $product->img_path = $path;
        }

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
