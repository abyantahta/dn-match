<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DemosImport;
use App\Models\Demo;
use Yajra\DataTables\Facades\DataTables;

class DemoController extends Controller
{
    //
    /**
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        $demosData = Demo::get();
        return view('demos.index', compact('demosData'));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function importDemo(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        Excel::import(new DemosImport, $request->file('file'));

        return back()->with('success', 'Users imported successfully.');
    }
    public function getData()
    {
        $query = Demo::query(); // Ganti dengan model yang sesuai

        return DataTables::of($query)
            ->make(true);
    }
}
