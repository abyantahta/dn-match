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
use App\Models\Interlock;
use App\Models\Pcc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
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
        $interlock = Interlock::get()->first();

        // dd($transactions);
        return view('matching.index', compact('transactions','interlock')); // Pastikan ini adalah view yang tepat
    }
    public function exportTransactions($plant, Request $request)
    {
        // $transactionExport = new TransactionsExport();
        // dd('plant')
        // dd($request->query('status_filter'));
        $dateFilter = $request->query('date_filter') ?: null;
        $statusFilter = $request->query('status_filter') ?: null;
        // $thisDay =  Carbon::today()->format('d-M-Y');
        // $yesterday = Carbon::yesterday()->format('d');
        // dd($thisDay, $yesterday);
        $fileName = $dateFilter? Carbon::parse($dateFilter)->format('d M Y'):"Full Transactions";

        if ($plant === "sap") {
            return Excel::download(new TransactionsExport($dateFilter,$statusFilter), "Log PCC Matching [".$fileName."] .xlsx");
        // } else if ($plant === "kep") {
        //     return Excel::download(new TransactionsKEPExport, "(ADM KEP) {$yesterday}|{$thisDay} .xlsx");
        // } else if ($plant === "kap") {
        //     return Excel::download(new TransactionsKAPExport, "(ADM KAP) {$yesterday}|{$thisDay} .xlsx");
        }
    }
    public function getTransactions(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::query()
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
                ->editColumn('created_at', function ($transaction) {
                    return $transaction->created_at->format('d/m/y - H:i'); // Ubah format tanggal di sini
                })

                ->rawColumns(['status', 'created_at']) // Jangan lupa tambahkan 'created_at' di sini
                ->make(true);
            // dd($transaction);
        }
    }
    public function store(Request $request)
    {
        $isLocked = Interlock::get()->first()->isLocked;
        // dd($isLocked);
        if($isLocked){
            return redirect()->back();
        }
        $input = trim($request->input('barcode')); // trim untuk menghilangkan whitespaces
        $input = str_replace(' ','',$input);
        // $input = str_replace(' ','',$part_no);
        // dd($input);
        // Check if input is Data1 (starting with "DN" and 26 characters in length DN5124100080185ARC-1066001)
        if (str_starts_with($input, '2503')) {
            // Check if input is Data1 (starting with "DN" and 26 characters in length DN5124100080185ARC-1066001)
            if (strlen($input) !== 23) {
                return redirect()->back()->withErrors($input . '(L:' . strlen($input) . ') INVALID FORMAT. PASTIKAN SCAN BARCODE SESUAI FORMAT YANG SDH DI REGISTER.');
            }
            
            // Cek apakah barcode_cust sudah ada di database
            $existingDn = Pcc::where('slip_barcode', $input)->latest()->first();
            $existingTransaction = Transaction::where('slip_barcode', $input)->where('status', "match")->latest()->first();
            if ($existingTransaction) {
                return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, ' . $input . '(L:' . strlen($input) . ') Barcode customer  sudah pernah berhasil di matching.');
            }
            // $last_match = Transaction::where('slip_barcode', $input)->latest()->first();
            if (!$existingDn) {
                return redirect()->back()->withErrors('<span class="badge bg-warning" > <b>NO DATA</b> </span>, ' . $input . ' Belum ada PCC yang di upload di sistem.');
            }
            // $dn_status = "OPEN"
            Session::put('temp_data', [
                'slip_barcode' => $input,
                'slip_no' => $existingDn->slip_no,
                'part_no' => str_replace(' ','',$existingDn->part_no),
                'part_name' => $existingDn->part_name,
                'del_date' => $existingDn->date,
                'pcc_seq' => $existingDn->pcc_count,
                'status' => 'pairing',
                'created_at' => now()
                // 'order_kbn' => $order_kbn,
                // 'match_kbn' => $match_kbn,
                // 'dn_status' => $dn_status,
                // 'plant' => $plant,
            ]);

            // Store no_dn and no_job in persistent session variables
            Session::put('slip_barcode', $input);
            Session::put('slip_no', $existingDn->slip_no);
            Session::put('part_no', $existingDn->part_no);
            Session::put('part_name', $existingDn->part_name);
            Session::put('del_date', $existingDn->date);
            Session::put('pcc_seq', $existingDn->pcc_count);
            Session::put('status', 'pairing');
            // Session::put('order_kbn', $order_kbn);
            // Session::put('match_kbn', $match_kbn);
            // Session::put('dn_status', $dn_status);
            // Session::put('created_at', $dn_status);

            return redirect()->back()->with('message', $input . '(L:' . strlen($input) . ') SILAHKAN SCAN BARCODE FG.');
        }

            $pattern = '/^\d{5}-\d[A-Z]\d-[A-Z0-9]{4}-[A-Z0-9]{2}\d{3}$/';
        if (preg_match($pattern, $input)) {

            $tempData = Session::get('temp_data');
            // dd($input);
            if (!$tempData) {
                return redirect()->back()->withErrors('SCAN BARCODE CUSTOMER SEBELUM BARCODE FG.');
            }
            // if (strlen($input) === 11) {
            // if(strlen($input===11))?
            $part_no = substr($input, 0, 17);   // Extract "BX-yyyy"
            $fg_seq = substr($input, -3);     // Extract "zzz"
            
            // $transactionExist = Transaction::where('part')


            Session::flash('barcode_fg', $input);
            Session::flash('part_no_fg', $part_no);
            // Session::flash('part_name_fg', $part_no);
            Session::flash('no_seq_fg', $fg_seq);
            // dd($tempData);
            // Compare no_job from Data1 and no_job_fg from Data2
            // dd($tempData);
            if ($tempData['part_no'] !== str_replace(' ','',$part_no)) {
                $transaction = new Transaction();
                $transaction->slip_barcode = $tempData['slip_barcode'];
                $transaction->part_no_pcc = $tempData['part_no'];
                $transaction->part_no_fg = $part_no;
                $transaction->seq_fg = $fg_seq;
                $transaction->del_date = $tempData['del_date'];
                $transaction->status = 'mismatch';
                $transaction->created_at = Carbon::now();
                $transaction->save();

                Interlock::query()->first()->update([
                    'isLocked'=>true,
                    'created_at'=> Carbon::now(),
                    'part_no_pcc'=> $tempData['part_no'],
                    'part_no_fg'=> $part_no
                ]);
                // $timeNow = ;
                // dd(Carbon::now()->format('H:i'));
                try{
                    $response = Http::withHeaders([
                        'Authorization' => 'DcjkiWJ9gwbp7scYKowe',
                    ])->withOptions(['verify' => false])->post('https://api.fonnte.com/send',[
                        'target'=> '085876366469',
                        'message' => 'Terjadi mismatch pukul '. Carbon::now()->format('H:i'). '
Segera datang ke line.'
                    ]);
                }catch(\Exception $e){

                }

                // Post::where('id', 1)->update(['title' => 'New Title', 'content' => 'Updated content']);
                // $transaction->barcode_fg = $input;
                // $transaction->no_job_fg = $no_job_fg;
                // $transaction->no_seq_fg = $no_seq_fg;
                // $transaction->status = 'mismatch';
                // $transaction->dn_status = $tempData['dn_status'];
                // $transaction->order_kbn = $tempData['order_kbn'];
                // $transaction->match_kbn = $tempData['match_kbn'];
                // $transaction->del_cycle = $tempData['del_cycle'];
                // $transaction->plant = $tempData['plant'];
                // dd($transaction->plant);

                // $table->id();
                // $table->string('slip_barcode')->nullable();
                // $table->string('part_no_pcc')->nullable();
                // $table->string('part_no_fg')->nullable();
                // $table->string('seq_fg')->nullable();
                // $table->enum('status',['paired','match','mismatch','not_match']);
                // $table->timestamps();
                // dd($transaction);
                return redirect()->back()
                    ->with('message-no-match', '<b>' . $part_no . '</b>, TIDAK SESUAI. SCAN ULANG !')
                    ->with('message', 'SILAHKAN SCAN KEMBALI BARCODE FG.');
                    // ->with();
            }
            //cek kalau ada transaksi dengan sequence number label yang sama, kalau sama, ditolak
            $transactionWithDupsSeqNo = Transaction::where('part_no_fg',$part_no)->where('seq_fg',$fg_seq)->where('created_at', '>=', Carbon::now()->subHours(12))->count();
            // dd($transactionWithDupsSeqNo);
            if($transactionWithDupsSeqNo != 0){
                // return back()->with('error','Label Finish Good Part No'.$input. " sudah pernah digunakan.");
                return redirect()->back()->withErrors('<span class="badge bg-warning" ><b>DOUBLE</b></span>, Label Finish Good '.$input. " sudah pernah digunakan");

            }
            // dd($tempData['match_kbn']);
            // $match_kbn = $tempData['match_kbn'] + 1;
            // if ($match_kbn >= $tempData['order_kbn']) {
            //     $dn_status = "close";
            // } else {
            //     $dn_status = $tempData['dn_status'];
            // }
            // Save the matched transaction if the job numbers match
            $transaction = new Transaction();
            $transaction->slip_barcode = $tempData['slip_barcode'];
            $transaction->part_no_pcc = $tempData['part_no'];
            $transaction->part_no_fg = $part_no;
            $transaction->seq_fg = $fg_seq;
            $transaction->status = 'match';
            $transaction->created_at = now();
            $transaction->save();

            Pcc::where('slip_barcode',$tempData['slip_barcode'])->first()->update(['isMatch'=>true]);

            // membuat data Dashboard
            // if ($dn_status == 'close') {
            //     Dashboard::where('no_dn', $tempData['no_dn'])->where('no_job', $tempData['no_job'])->update([
            //         "kanban_match" => $match_kbn . "/" . $tempData['order_kbn'],
            //         "dn_status" => $dn_status
            //     ]);
            // } else {
            //     Dashboard::where('no_dn', $tempData['no_dn'])->where('no_job', $tempData['no_job'])->update([
            //         "kanban_match" => $match_kbn . "/" . $tempData['order_kbn'],
            //     ]);
            // }
            // $dataDashboard->cycle = $tempData['del_cycle'];
            // $dataDashboard->dn_number = $tempData['del_cycle'];
            // Clear temporary session data for data1
            Session::forget('temp_data');
            // Session::forget('temp_data');

            // Call the resetSession function to clear session data
            $this->resetSession();

            return redirect()->back()->with('message-match', 'MATCH,<br>SLIP BARCODE: <b>' . $tempData['slip_no'] . '</b>, PART: <b>' . $tempData['part_no'] . '</b>,'. '</b><br> TRANSAKSI BERHASIL DI SIMPAN. ');
        }

        // Handle invalid input formats for both data1 and data2
        return redirect()->back()->withErrors($input . '(L:' . strlen($input) . ') INVALID FORMAT. PASTIKAN SCAN BARCODE SESUAI FORMAT YANG SDH DI REGISTER.');
    }

    public function unlock (Request $request){
        $passkey = trim($request->input('passkey')); // trim untuk menghilangkan whitespaces
        // dd($passkey);
        if($passkey !== "triwanto123"){
            return redirect()->back()->with('passkey_error','Passkey salah!');
        }
        Interlock::query()->first()->update(['isLocked'=>false]);
        return redirect()->back();
    }



    // MatchingController.php
    public function resetSession()
    {
        // Hapus data no_dn dan no_job dari session
        Session::forget('slip_barcode');
        Session::forget('slip_no');
        Session::forget('part_no');
        Session::forget('part_name');
        Session::forget('del_date');
        Session::forget('order_kbn');
        Session::forget('match_kbn');
        Session::forget('dn_status');
        Session::forget('pcc_seq');
        Session::forget('barcode_fg');
        Session::forget('part_no_fg');
        Session::forget('no_seq_fg');
        // Session::forget('match_kbn');
        // Session::forget('del_cycle');

        // Redirect kembali ke halaman input dengan pesan
        return redirect()->back()->with('message-reset', 'Session has been reset.');
    }
}
