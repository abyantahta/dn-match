<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Imports\OrderDeliveryImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\OrderDelivery;

class OrderDeliveryController extends Controller
{
    //

    public function index()
    {
        return view('order_delivery.index');
    }
    //
    public function importOrderDelivery(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        Excel::import(new OrderDeliveryImport, $request->file('file'));

        return redirect()->back()->with('success', 'Order Delivery data has been successfully imported.');
    }




    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new OrderDeliveryImport, $request->file('file'));
        
        return redirect()->back()->with('success', 'Data berhasil diunggah');
    }

    public function getDn(Request $request)
    {
        if ($request->ajax()) {
            $data = OrderDelivery::all();
            return DataTables::of($data)->make(true);
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }
}
