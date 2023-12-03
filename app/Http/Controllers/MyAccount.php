<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MyAccount extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $status = 404;
            if (Hash::check(request()->key, auth()->user()->password)) {
                $status = 200;
            }

            return response()->json([
                'status' => $status,
            ]);
        }
        $image_og = '';
        $orders = Bill::where('user_id', auth()->id())->get();
        return view('FrontEnd.MyAccount', compact('image_og', 'orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find(auth()->id());
        $user->phone = $request->phone;
        $user->address = $request->address;
        if ($request->passwordCurrent != null) {
            $user->password = Hash::make($request->newPassword);
        }
        $user->save();

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
        $detail = BillDetail::select('bill_detail.*', 'a.id as pro_id', 'p.sp_vi', 'p.sp_en')
            ->join('products as a', 'a.id', 'bill_detail.id_product')
            ->join('post as p', 'p.id_post', 'bill_detail.id_post_bill_detail')
            ->where('order_code', $id)
            ->get();
        $customer = Customer::select('customer.*', 'b.id_bill as orderid', 'b.status_bill')
            ->join('bills as b', 'b.id_customer', 'customer.id')
            ->where('b.order_code', $id)
            ->first();
        $order = Bill::where('order_code', $id)->first();

        return response()->json([
            'detail' => $detail,
            'customer' => $customer,
            'totalPrice' => number_format($order->total),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
