<?php

namespace App\Http\Controllers;

use App\Models\Demo;
use App\Models\DnADM;
use App\Models\DnADMKEP;
use App\Models\DnADMKAP;
use App\Imports\DemosImport;
use App\Imports\DnADMImport;
use App\Imports\DnADMKAPImport;
use App\Imports\DnADMKEPImport;
use App\Models\Interlock;
use App\Models\Pcc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DnController extends Controller
{
    //
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sap()
    {
        $dateFilter = request('date_filter', '');  // default value, for instance
        $statusFilter = request('status_filter', '');
        $interlock = Interlock::get()->first();
        $dnData = Pcc::get();
        return view('dn.adm.sap', compact('dnData','dateFilter','statusFilter','interlock'));
    }
    // public function kep()
    // {
    //     $dnData = DnADMKEP::get();
    //     return view('dn.adm.kep', compact('dnData'));
    // }    
    // public function kap()
    // {
    //     $dnData = DnADMKAP::get();
    //     return view('dn.adm.kap', compact('dnData'));
    // }


    public function getDnADMSAPData(Request $request)
    {
        // dd();
        $query = Pcc::query(); // Ganti dengan model yang sesuai
        if($request->created_at){
            $query->whereDate('date',$request->created_at);
        }
        // if (!empty($request->pccStatus)) {
            if ($request->pccStatus == 'matched') {
                $query->where('isMatch', 1);
            } else if ($request->pccStatus == 'unmatched') {
                $query->where('isMatch', 0);
            }
        // }
        // dd($request->pccStatus);
        // Log::info('Request Data:'.$request->pccStatus. 'Date: '. $request->created_at);

        
        // if($request->pcc_status =='matched'){
        //     // dd('sini');
        //     $query->where('isMatch',1);
        // }else if($request->pcc_status == 'unmatched'){
        //     $query->where('isMatch',0);
        // }

        // return DataTables::of($query)
        //     ->make(true);

        return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('isMatch', function ($transaction) {
            return '<span class="badge text-center ' . ($transaction->isMatch? 'bg-success' : 'bg-danger') . '">' . ($transaction->isMatch? 'Matched' : 'Unmatched') . '</span>';
        })
        ->rawColumns(['isMatch']) // Jangan lupa tambahkan 'created_at' di sini
        ->make(true);
    }
    // public function getDnADMSAPData(Request $request, DataTables $dataTables)
    // {
    //     // dd($request->created_at);
    //     $query = Pcc::query(); // Ganti dengan model yang sesuai

    //     return $dataTables->eloquent($query)->make(true);
    // }
    // public function getDnADMKEPData()
    // {
    //     $query = DnADMKEP::query(); // Ganti dengan model yang sesuai

    //     return DataTables::of($query)
    //         ->make(true);
    // }    
    // public function getDnADMKAPData()
    // {
    //     $query = DnADMKAP::query(); // Ganti dengan model yang sesuai

    //     return DataTables::of($query)
    //         ->make(true);
    // }

    /**
     * @return \Illuminate\Support\Collection
     */
    // public function importDnADM(Request $request)
    // {
    //     // Validate incoming request data
    //     $request->validate([
    //         'file' => 'required|max:2048',
    //     ]);
    //     // dd($request);

    //     try {
    //         Excel::import(new DnADMImport, $request->file('file'));
    //         return back()->with('success', 'DNs imported successfully.');
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         // Tangkap kesalahan SQL dan tampilkan pesan kesalahan
    //         $errorMessage = $e->getMessage();
    //         return back()->with('error', 'SQL Error: ' . $errorMessage);
    //     } catch (\Exception $e) {
    //         // Tangkap kesalahan umum lainnya
    //         $errorMessage = $e->getMessage();
    //         return back()->with('error', 'Error: ' . $errorMessage);
    //     }
    // }
    // public function importDnADMKEP(Request $request)
    // {
    //     // Validate incoming request data
    //     $request->validate([
    //         'file' => 'required|max:2048',
    //     ]);

    //     try {
    //         Excel::import(new DnADMKEPImport, $request->file('file'));
    //         return back()->with('success', 'DNs imported successfully.');
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         // Tangkap kesalahan SQL dan tampilkan pesan kesalahan
    //         $errorMessage = $e->getMessage();
    //         return back()->with('error', 'SQL Error: ' . $errorMessage);
    //     } catch (\Exception $e) {
    //         // Tangkap kesalahan umum lainnya
    //         $errorMessage = $e->getMessage();
    //         return back()->with('error', 'Error: ' . $errorMessage);
    //     }
    // }
    // public function importDnADMKAP(Request $request)
    // {
    //     // Validate incoming request data
    //     $request->validate([
    //         'file' => 'required|max:2048',
    //     ]);

    //     try {
    //         Excel::import(new DnADMKAPImport, $request->file('file'));
    //         return back()->with('success', 'DNs imported successfully.');
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         // Tangkap kesalahan SQL dan tampilkan pesan kesalahan
    //         $errorMessage = $e->getMessage();
    //         return back()->with('error', 'SQL Error: ' . $errorMessage);
    //     } catch (\Exception $e) {
    //         // Tangkap kesalahan umum lainnya
    //         $errorMessage = $e->getMessage();
    //         return back()->with('error', 'Error: ' . $errorMessage);
    //     }
    // }



    // public function saveDnADM(Request $request)
    // {
    //     // Logic untuk menyimpan data ke database
    //     // Anda bisa menggunakan Excel::import() seperti sebelumnya
    //     Excel::import(new DnADMImport, $request->file('file'));

    //     return back()->with('success', 'DNs imported successfully.');
    // }
}
