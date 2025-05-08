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
use App\Models\Pcc;
use Illuminate\Http\Request;
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
        $dnData = DnADM::get();
        return view('dn.adm.sap', compact('dnData'));
    }
    public function kep()
    {
        $dnData = DnADMKEP::get();
        return view('dn.adm.kep', compact('dnData'));
    }    
    public function kap()
    {
        $dnData = DnADMKAP::get();
        return view('dn.adm.kap', compact('dnData'));
    }


    public function getDnADMSAPData()
    {
        $query = Pcc::query(); // Ganti dengan model yang sesuai

        return DataTables::of($query)
            ->make(true);
    }
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
    public function importDnADM(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);
        // dd($request);

        try {
            Excel::import(new DnADMImport, $request->file('file'));
            return back()->with('success', 'DNs imported successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap kesalahan SQL dan tampilkan pesan kesalahan
            $errorMessage = $e->getMessage();
            return back()->with('error', 'SQL Error: ' . $errorMessage);
        } catch (\Exception $e) {
            // Tangkap kesalahan umum lainnya
            $errorMessage = $e->getMessage();
            return back()->with('error', 'Error: ' . $errorMessage);
        }
    }
    public function importDnADMKEP(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        try {
            Excel::import(new DnADMKEPImport, $request->file('file'));
            return back()->with('success', 'DNs imported successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap kesalahan SQL dan tampilkan pesan kesalahan
            $errorMessage = $e->getMessage();
            return back()->with('error', 'SQL Error: ' . $errorMessage);
        } catch (\Exception $e) {
            // Tangkap kesalahan umum lainnya
            $errorMessage = $e->getMessage();
            return back()->with('error', 'Error: ' . $errorMessage);
        }
    }
    public function importDnADMKAP(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        try {
            Excel::import(new DnADMKAPImport, $request->file('file'));
            return back()->with('success', 'DNs imported successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap kesalahan SQL dan tampilkan pesan kesalahan
            $errorMessage = $e->getMessage();
            return back()->with('error', 'SQL Error: ' . $errorMessage);
        } catch (\Exception $e) {
            // Tangkap kesalahan umum lainnya
            $errorMessage = $e->getMessage();
            return back()->with('error', 'Error: ' . $errorMessage);
        }
    }



    public function saveDnADM(Request $request)
    {
        // Logic untuk menyimpan data ke database
        // Anda bisa menggunakan Excel::import() seperti sebelumnya
        Excel::import(new DnADMImport, $request->file('file'));

        return back()->with('success', 'DNs imported successfully.');
    }
}
