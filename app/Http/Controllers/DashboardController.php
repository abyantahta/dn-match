<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index(){
        $dashboardData = Dashboard::all();
        // dd($dashboardData);
        return view('dashboard.index',compact('dashboardData'));
    }
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Dashboard::select(['no_dn', 'no_job','kanban_match','del_cycle','dn_status','created_at'])
            ->latest('created_at'); // Tambahkan ini untuk urutkan dari terbaru;

            return DataTables::of($transactions)
                ->addIndexColumn()
                ->editColumn('dn_status', function ($transaction) {
                    $statusClass = 'bg-danger';
                    $statusText = 'NA';

                    if ($transaction->dn_status == 'OPEN'|| $transaction->dn_status == 'open') {
                        $statusClass = 'bg-warning';
                        $statusText = ucfirst($transaction->dn_status);
                    } elseif ($transaction->dn_status == 'CLOSE'|| $transaction->dn_status == 'close') {
                        $statusClass = 'bg-success';
                        $statusText = ucfirst($transaction->dn_status);
                    }

                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })
                ->editColumn('created_at', function ($transaction) {
                    return $transaction->created_at->toDateString(); // Ubah format tanggal di sini
                })
                ->rawColumns(['dn_status', 'created_at']) // Jangan lupa tambahkan 'created_at' di sini
                ->make(true);
                // dd($transaction);
        }
    }
}
