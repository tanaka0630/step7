<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Support\Facades\Log;

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

        if ($request->ajax()){
            // ソートの処理を handleSortClick メソッドに委譲
            return $this->handleSortClick($request->input('sort_column'), $request->input('sort_direction'));
        }

        $keyword = $request->input('keyword');
        $companyName = $request->input('company_name');

        $priceUpper = $request->input('price_upper');
        $priceLower = $request->input('price_lower');
        $stockUpper = $request->input('stock_upper');
        $stockLower = $request->input('stock_lower');

        $query = Products::query();

        // $products = $query->orderBy('created_at', 'desc')->get();


        if (!empty($keyword)) {
            $query->where('product_name', 'like', '%' . $keyword . '%');
        }

        if (!empty($companyName)) {
            $query->where('company_id', $companyName);
        }

        if (!empty($priceUpper)) {
            $query->where('price', '<=', $priceUpper);
        }
        if (!empty($priceLower)) {
            $query->where('price', '>=', $priceLower);
        }
        if (!empty($stockUpper)) {
            $query->where('stock', '<=', $stockUpper);
        }
        if (!empty($stockLower)) {
            $query->where('stock', '>=', $stockLower);
        }

        // $products = $query->sortable()->get();
        // Log::info($products);

        $products = $query->get();


        // if ($request->ajax()){
        //     return response()->json(['products' => $products],200);
        // }


        // 検索結果をビューに渡す
        return view('products', compact('products', 'companies'));
        

        

    }

    public function search(Request $request){
        Log::info($request);

        $companies = Companies::all();

        $keyword = $request->input('keyword');
        $companyName = $request->input('company_name');


        $priceUpper = $request->input('price_upper');
        $priceLower = $request->input('price_lower');
        $stockUpper = $request->input('stock_upper');
        $stockLower = $request->input('stock_lower');

        $query = Products::query();

        // $products = $query->orderBy('created_at', 'desc')->get();

        if (!empty($keyword)) {
            $query->where('product_name', 'like', '%' . $keyword . '%');
        }

        if (!empty($companyName)) {
            $query->where('company_id', $companyName);
        }

        if (!empty($priceUpper)) {
            $query->where('price', '<=', $priceUpper);
        }
        if (!empty($priceLower)) {
            $query->where('price', '>=', $priceLower);
        }
        if (!empty($stockUpper)) {
            $query->where('stock', '<=', $stockUpper);
        }
        if (!empty($stockLower)) {
            $query->where('stock', '>=', $stockLower);
        }

        

        $products = $query->with('company')->sortable()->get();

        Log::info($products);
        

        //検索結果をビューに渡す
        if ($request->ajax()) {
            return response()->json(['products' => $products->toArray()], 200);
        }

        return view('products', compact('products', 'companies'));

        Log::info($companyName);
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
        // バリデーションルール
        $rules = [
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable',
            'img_path' => 'nullable|image',
        ];

        // バリデーションメッセージ
        $messages = [
            'required' => '*必須項目です。',
            'numeric' => '*必須項目です',
        ];

        // バリデーション実行
        $request->validate($rules, $messages);

        // トランザクション開始
        DB::beginTransaction();

        try {
            // モデルの処理を呼び出して商品を保存
            $product = Products::storeProduct($request);

            // トランザクションコミット
            DB::commit();

            return redirect()->route('products.index')
                ->with('success', '商品が新規登録されました');
        } catch (\Exception $e) {
            // エラーが発生した場合はトランザクションロールバック
            DB::rollBack();

            return redirect()->route('products.index')
                ->with('error', '商品の新規登録に失敗しました')
                ->withErrors($e->getMessage());
        }
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

        if ($product === null) {
            echo('詳細');
            abort(404, 'Product not found');
        }


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
        // トランザクション開始
        DB::beginTransaction();

        try {
            // モデルの処理を呼び出して商品を更新
            $product = Products::updateProduct($request, $id);

            // トランザクションコミット
            DB::commit();

            return redirect()->route('products.index')
                ->with('success', '商品情報が更新されました');
        } catch (\Exception $e) {
            // エラーが発生した場合はトランザクションロールバック
            DB::rollBack();

            return redirect()->route('products.index')
                ->with('error', '商品情報の更新に失敗しました')
                ->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $product = Products::find($id);

            if ($product) {
                $product->delete();

                // トランザクションコミット
                DB::commit();

                return response()->json(['message' => '商品が削除されました', 'deleteProductId' => $id]);
            } else {
                // 商品が見つからない場合もトランザクションロールバック
                DB::rollBack();

                return response()->json(['error' => '商品が見つかりません'], 404);
            }
        } catch (\Exception $e) {
            // エラーが発生した場合はトランザクションロールバック
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleSortClick($column)
    {
        $direction = request()->has('direction') ? request()->input('direction') : 'asc';
        $products = Products::sortable([$column => $direction])->paginate(10); // ページネーションの例、必要に応じて調整
        return response()->json(['products' => $products], 200);
        log::info($column);
    }
}
