<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\ImportDetail;
use App\Models\Product;
use App\Models\ProductType;
// use Barryvdh\DomPDF\Facade as PDF;
use App\Models\User;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imports = Import::all();

        return view('admin.Phieunhap', compact('imports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $products = Product::join('post as p', 'p.id_post', 'products.id_post')->get();
        $category = ProductType::all();
        return view('admin.Phieunhap_thaotac', compact('users', 'products', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function FormatPrice($price)
    {
        return floatval(preg_replace('/[^\d.]/', '', $price));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $user = explode('|', $request->user_id);

        $import = new Import();
        $import->user_id = $user[0];
        $import->total_price = $this->FormatPrice($request->tongTienHien);
        $import->debt = $this->FormatPrice($request->tongTienHien) - $this->FormatPrice($request->daThanhToan);
        $import->note = $request->note;
        $import->save();

        foreach ($request->name as $key => $item) {
            $product = explode('|', $item);
            $category = explode('|', $request->category[$key]);
            $id_product = trim($product[0], 'SP');

            $import_detail = new ImportDetail();
            $import_detail->import_id = $import->id;
            $import_detail->product_id = $id_product;
            $import_detail->qty = $request->qty[$key];
            $import_detail->price = $this->FormatPrice($request->price[$key]);
            $import_detail->save();

            $product = Product::find($id_product);
            $product->product_quantity = $request->qty[$key] + $product->product_quantity;
            $product->price_import = $this->FormatPrice($request->price[$key]);
            $product->id_type = $category[0];
            $product->date_sale = $request->date[$key];
            $product->save();
        }

        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::all();
        $products = Product::join('post as p', 'p.id_post', 'products.id_post')->get();
        $import = Import::find($id);
        $category = ProductType::all();
        return view('admin.Phieunhap_thaotac', compact('users', 'products', 'import', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $import = Import::find($id);
        $importDetail = ImportDetail::where('import_id', $id)->get();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('admin.Phieunhap_pdf', compact('import', 'importDetail'));
        return $pdf->stream('PN' . $import->id . '.pdf');
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
        // dd($request->all());
        $user = explode('|', $request->user_id);

        $import = Import::find($id);
        $import->user_id = $user[0];
        $import->total_price = $this->FormatPrice($request->tongTienHien);
        $import->debt = $this->FormatPrice($request->tongTienHien) - $this->FormatPrice($request->daThanhToan);
        $import->note = $request->note;
        $import->save();

        $detail = ImportDetail::where('import_id', $id)->get();
        foreach ($detail as $row) {
            $qty = $row->ImportDetailToProduct->product_quantity - $row->qty;
            $row->ImportDetailToProduct->product_quantity = $qty > 0 ? $qty : 0;
            $row->ImportDetailToProduct->save();
            $row->delete();
        }

        foreach ($request->name as $key => $item) {
            $product = explode('|', $item);
            $category = explode('|', $request->category[$key]);
            $id_product = trim($product[0], 'SP');

            $import_detail = new ImportDetail();
            $import_detail->import_id = $import->id;
            $import_detail->product_id = $id_product;
            $import_detail->qty = $request->qty[$key];
            $import_detail->price = $this->FormatPrice($request->price[$key]);
            $import_detail->save();

            $product = Product::find($id_product);
            $product->product_quantity = $request->qty[$key] + $product->product_quantity;
            $product->price_import = $this->FormatPrice($request->price[$key]);
            $product->id_type = $category[0];
            $product->date_sale = $request->date[$key];
            $product->save();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $import = Import::find($id);
        foreach ($import->ImportToDetail as $row) {
            $product = Product::find($row->product_id);
            $product->product_quantity = 0;
            $product->id_type = null;
            $product->date_sale = now()->format('Y-m-d H:i');
            $product->save();
            $row->delete();
        }
        $import->delete();

        return back();
    }
}
