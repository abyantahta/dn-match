<?php

namespace App\Http\Controllers;

use App\Models\DnADM;
use App\Models\DnADMKEP;
use App\Models\Dashboard;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Exports\TransactionsExport;
use App\Exports\TransactionsKEPExport;
use App\Exports\TransactionsKAPExport;
use App\Models\DnADMKAP;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class MatchingController extends Controller
{
    // public function index()
    // {
    //     // Display all transactions in the summary view
    //     $transactions = Transaction::all();
    //     return view('matching.index', compact('transactions'));
    // }
    public function index()
    {
        // Untuk menampilkan view DataTable
        $transactions = Transaction::all();
        // dd($transactions);
        return view('matching.index', compact('transactions')); // Pastikan ini adalah view yang tepat
    }
    public function exportTransactions($plant){
        // $transactionExport = new TransactionsExport();
        // dd('plant')
        $thisDay =  Carbon::today()->format('d-M-Y');
        $yesterday = Carbon::yesterday()->format('d');
        // dd($thisDay, $yesterday);

        if($plant==="sap"){
            return Excel::download(new TransactionsExport,"(ADM SAP) {$yesterday}|{$thisDay} .xlsx");
        }else if($plant==="kep"){
            return Excel::download(new TransactionsKEPExport,"(ADM KEP) {$yesterday}|{$thisDay} .xlsx");
        }else if($plant==="kap"){
            return Excel::download(new TransactionsKAPExport,"(ADM KAP) {$yesterday}|{$thisDay} .xlsx");
        }
    }
    public function getTransactions(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::select(['barcode_cust', 'no_dn', 'no_job', 'no_seq', 'barcode_fg', 'no_job_fg', 'no_seq_fg', 'status', 'dn_status', 'order_kbn', 'match_kbn','del_cycle','plant', 'created_at'])
            // $transactions = Transaction::select(['barcode_cust', 'no_dn', 'no_job', 'no_seq', 'barcode_fg', 'no_job_fg', 'no_seq_fg', 'status', 'dn_status', 'order_kbn', 'match_kbn','del_cycle', 'created_at'])
            ->latest('created_at'); // Tambahkan ini untuk urutkan dari terbaru;
            // dd($transaction)
            // {{ dd($transaction) }};
            // dd($transactions);
            return DataTables::of($transactions)
                ->addIndexColumn()
                ->editColumn('status', function ($transaction) {
                    return '<span class="badge ' . ($transaction->status == 'match' ? 'bg-success' : 'bg-danger') . '">' . ucfirst($transaction->status) . '</span>';
                })
                ->editColumn('dn_status', function ($transaction) {
                    $statusClass = 'bg-danger';
                    $statusText = 'NA';

                    if ($transaction->dn_status == 'open') {
                        $statusClass = 'bg-warning';
                        $statusText = ucfirst($transaction->dn_status);
                    } elseif ($transaction->dn_status == 'close') {
                        $statusClass = 'bg-success';
                        $statusText = ucfirst($transaction->dn_status);
                    }

                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })
                ->editColumn('created_at', function ($transaction) {
                    return $transaction->created_at->format('d/m/y - H:i'); // Ubah format tanggal di sini
                })
                ->editColumn('plant', function ($transaction) {
                    $statusClass = 'bg-primary';
                    $statusText = $transaction->plant;

                    if ($transaction->plant == 'ADM KAP') {
                        $statusClass = 'bg-secondary';
                        // $statusText = ucfirst($transaction->plant);
                    } elseif ($transaction->plant == 'ADM KEP') {
                        $statusClass = 'bg-info';
                        // $statusText = ucfirst($transaction->plant);
                    }

                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })
                ->rawColumns(['status', 'dn_status', 'created_at','plant']) // Jangan lupa tambahkan 'created_at' di sini
                ->make(true);
                // dd($transaction);
        }
    }
    public function store(Request $request)
    {
        $input = trim($request->input('barcode')); // trim untuk menghilangkan whitespaces

        // Check if input is Data1 (starting with "DN" and 26 characters in length DN5124100080185ARC-1066001)
        if (str_starts_with($input, 'DN') || str_starts_with($input, 'SO')) {
            // Check if input is Data1 (starting with "DN" and 26 characters in length DN5124100080185ARC-1066001)
            if (strlen($input) === 26) {
                $no_dn =  substr($input, 0, -10);     // Extract "DNxxxxxxxxxxxxxAAA"
                $no_job = substr($input, -10, 7);     // Extract "AAA-yyyy"
                $no_seq = substr($input, -3);        // Extract "zzz"
            } else if (strlen($input) === 31) { // DN21241000527217400-BZ040-00001
                $no_dn =  substr($input, 0, -17);     // Extract "DNxxxxxxxxxxxxxAAA"
                $no_job = substr($input, -17, 14);     // Extract "AAA-yyyy"
                $no_seq = substr($input, -3);        // Extract "zzz"
                // dd($no_dn,$no_job,$no_seq);
            } else {
                // Handle invalid input formats for both data1 and data2
                return redirect()->back()->withErrors($input . '(L:' . strlen($input) . ') INVALID FORMAT. PASTIKAN SCAN BARCODE SESUAI FORMAT YANG SDH DI REGISTER.');
            }

            // Cek apakah barcode_cust sudah ada di database
            $existingTransaction = Transaction::where('barcode_cust', $input)->where('status', "match")->latest()->first();

            if ($existingTransaction) {
                return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') Barcode customer  sudah pernah berhasil di matching.');
            }
            // dd($no_dn,$no_job);
            // $existingDn = (strlen($input) === 26) ? DnADM::where('order_no', $no_dn)->where('job_no', $no_job)->latest()->first()
            // : DnADMKEP::where('dn_no', $no_dn)->where('part_no', $no_job)->latest()->first();
            $existingDn = DnADM::where('order_no', $no_dn)->where('job_no', $no_job)->latest()->first();
            if(!$existingDn && strlen($input)===26){
                $existingDn = DnADMKAP::where('order_no', $no_dn)->where('job_no', $no_job)->latest()->first();
            }else if(!$existingDn){
                $existingDn = DnADMKEP::where('dn_no', $no_dn)->where('part_no', $no_job)->latest()->first();
            }
            // dd($existingDn);
            $last_match = Transaction::where('no_dn', $no_dn)->where('no_job', $no_job)->latest()->first();
            // dd($existingDn);
            if ($existingDn) {
                $order_kbn = (strlen($input) === 26)?  $existingDn->order_kbn : $existingDn->qty_kanban;
                $plant = "ADM SAP";
                if($existingDn->plant_code==="D102") $plant="ADM KEP";
                if($existingDn->plant_code==="D105") $plant="ADM KAP";
                $del_cycle = $existingDn->del_cycle;

                if ($last_match) {
                    $dn_status = $last_match->dn_status;
                        if($dn_status == 'close'){
                            return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') DN Status Close, Seluruh kanban dalam DN sudah berhasil match');
                        }
                    // dd($last_match);
                    $match_kbn = $last_match->match_kbn;
                    // dd($match_kbn);

                } else {
                    $match_kbn = 0;
                    $dn_status = "open";
                }

                // dd($order_kbn);
            } else {
                return redirect()->back()->withErrors('<span class="badge bg-warning" > <b>NO DATA</b> </span>, ' . $no_dn . ' Belum ada DN yang di upload di sistem.');
            }
            // Store data1 temporarily in session and prepare for data2
            Session::put('temp_data', [
                'barcode_cust' => $input,
                'no_dn' => $no_dn,
                'no_job' => $no_job,
                'no_seq' => $no_seq,
                'order_kbn' => $order_kbn,
                'match_kbn' => $match_kbn,
                'del_cycle' => $del_cycle,
                'dn_status' => $dn_status,
                'plant' => $plant,
                'status' => 'pairing',
                'created_at' => now()
            ]);

            // Store no_dn and no_job in persistent session variables
            Session::put('barcode_cust', $input);
            Session::put('no_dn', $no_dn);
            Session::put('no_job', $no_job);
            Session::put('no_seq', $no_seq);
            Session::put('order_kbn', $order_kbn);
            Session::put('match_kbn', $match_kbn);
            Session::put('del_cycle', $del_cycle);
            Session::put('plant', $plant);
            Session::put('dn_status', $dn_status);

            return redirect()->back()->with('message', $input . '(L:' . strlen($input) . ') SILAHKAN SCAN BARCODE FG.');
        }

        // Check if input is Data2 with the updated format "BX-yyyy-zzz"
        if (preg_match('/-[\d]{3}$/', $input)| preg_match('/-[\d]{5}$/', $input))  {

            $tempData = Session::get('temp_data');

            if (!$tempData) {
                return redirect()->back()->withErrors('SCAN BARCODE CUSTOMER SEBELUM BARCODE FG.');
            }
            // if (strlen($input) === 11) {
            // if(strlen($input===11))?
            $no_job_fg = (strlen($input)===11)? substr($input, 0, -4) : substr($input, 0, -3);   // Extract "BX-yyyy"
            $no_seq_fg = substr($input, -3);     // Extract "zzz"
            // }
            // dd($no_job_fg);
            Session::flash('barcode_fg', $input);
            Session::flash('no_job_fg', $no_job_fg);
            Session::flash('no_seq_fg', $no_seq_fg);
            // dd($tempData);
            // Compare no_job from Data1 and no_job_fg from Data2
            if ($tempData['no_job'] !== $no_job_fg) {
                Session::flash('no_job_fg', $no_job_fg);

                // Kembalikan dua pesan alert sekaligus
                // Save the matched transaction if the job numbers mismatch
                $transaction = new Transaction();
                $transaction->barcode_cust = $tempData['barcode_cust'];
                $transaction->no_dn = $tempData['no_dn'];
                $transaction->no_job = $tempData['no_job'];
                $transaction->no_seq = $tempData['no_seq'];
                $transaction->barcode_fg = $input;
                $transaction->no_job_fg = $no_job_fg;
                $transaction->no_seq_fg = $no_seq_fg;
                $transaction->status = 'mismatch';
                $transaction->dn_status = $tempData['dn_status'];
                $transaction->order_kbn = $tempData['order_kbn'];
                $transaction->match_kbn = $tempData['match_kbn'];
                $transaction->del_cycle = $tempData['del_cycle'];
                $transaction->plant = $tempData['plant'];
                // dd($transaction->plant);
                $transaction->created_at = now();
                $transaction->save();
                // dd($transaction);
                return redirect()->back()
                    ->with('message-no-match', '<b>' . $no_job_fg . '</b>, TIDAK SESUAI. SCAN ULANG !')
                    ->with('message', 'SILAHKAN SCAN KEMBALI BARCODE FG.');
            }
            // dd($tempData['match_kbn']);
            $match_kbn = $tempData['match_kbn'] + 1;
            if ($match_kbn >= $tempData['order_kbn']) {
                $dn_status = "close";
            } else {
                $dn_status = $tempData['dn_status'];
            }
            // Save the matched transaction if the job numbers match
            $transaction = new Transaction();
            $transaction->barcode_cust = $tempData['barcode_cust'];
            $transaction->no_dn = $tempData['no_dn'];
            $transaction->no_job = $tempData['no_job'];
            $transaction->no_seq = $tempData['no_seq'];
            $transaction->barcode_fg = $input;
            $transaction->no_job_fg = $no_job_fg;
            $transaction->no_seq_fg = $no_seq_fg;
            $transaction->status = 'match';
            $transaction->dn_status = $dn_status;
            $transaction->order_kbn = $tempData['order_kbn'];
            $transaction->match_kbn = $match_kbn;
            $transaction->del_cycle = $tempData['del_cycle'];
            // $transaction->plant = (strlen($tempData['no_dn'])===16)? 'ADM SAP':'ADM KEP';
            $transaction->plant = $tempData['plant'];
            $transaction->created_at = now();
            $transaction->save();
            
            // membuat data Dashboard
            if($dn_status=='close'){
                Dashboard::where('no_dn', $tempData['no_dn'])->where('no_job', $tempData['no_job'])->update([
                    "kanban_match"=> $match_kbn."/".$tempData['order_kbn'],
                    "dn_status"=> $dn_status
                ]);
            }else{
                Dashboard::where('no_dn', $tempData['no_dn'])->where('no_job', $tempData['no_job'])->update([
                    "kanban_match"=> $match_kbn."/".$tempData['order_kbn'],
                ]);
            }
            // $dataDashboard->cycle = $tempData['del_cycle'];
            // $dataDashboard->dn_number = $tempData['del_cycle'];
            // Clear temporary session data for data1
            Session::forget('temp_data');

            // Call the resetSession function to clear session data
            $this->resetSession();

            return redirect()->back()->with('message-match', 'MATCH,<br>DN: <b>' . $tempData['no_dn'] . '</b>, JOB: <b>' . $tempData['no_job'] . '</b>, SEQ: <b>' . $tempData['no_seq'] . '</b><br> TRANSAKSI BERHASIL DI SIMPAN. ');
        }

        // Handle invalid input formats for both data1 and data2
        return redirect()->back()->withErrors($input . '(L:' . strlen($input) . ') INVALID FORMAT. PASTIKAN SCAN BARCODE SESUAI FORMAT YANG SDH DI REGISTER.');
    }


    // Fungsi translator_job untuk menerjemahkan kode tertentu
    private function translator_job($jobCode)
    {
        $translations = [
            '17400-BZ060-00' => 'E2002',
            '17400-BZ040-00' => 'E2781',
            // Tambahkan pola lain di sini jika perlu
        ];

        return $translations[$jobCode] ?? $jobCode;
    }

    // MatchingController.php
    public function resetSession()
    {
        // Hapus data no_dn dan no_job dari session
        Session::forget('no_dn');
        Session::forget('no_job');
        Session::forget('no_seq');
        Session::forget('barcode_cust');
        Session::forget('no_job_fg');
        Session::forget('no_seq_fg');
        Session::forget('barcode_fg');
        Session::forget('dn_status');
        Session::forget('order_kbn');
        Session::forget('match_kbn');
        Session::forget('del_cycle');

        // Redirect kembali ke halaman input dengan pesan
        return redirect()->back()->with('message-reset', 'Session has been reset.');
    }
}
