<?php

namespace App\Exports;

use App\Models\Pcc;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionsExport implements FromView
{
    // public static $plant;
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $dateFilter;
    protected $statusFilter;

    // Constructor to accept filter parameters
    public function __construct($dateFilter, $statusFilter)
    {
        $this->dateFilter = $dateFilter;
        $this->statusFilter = $statusFilter;
    }

    public function view():View
    {
        $query = Pcc::query(); // Ganti dengan model yang sesuai
        $transactionQuery = Transaction::query(); // Ganti dengan model yang sesuai
        if($this->dateFilter){
            $query->whereDate('date',Carbon::parse($this->dateFilter));
            $transactionQuery->whereDate('del_date',Carbon::parse($this->dateFilter));
        }
        // if (!empty($request->pccStatus)) {
            if ($this->statusFilter == 'matched') {
                $query->where('isMatch', 1);
            } else if ($this->statusFilter == 'unmatched') {
                $query->where('isMatch', 0);
            }
            $results = $query->get();
            $transactions = $transactionQuery->get();
            // dd($results);
        // $transactions = Transaction::whereDate('created_at', '>', now()->subDays(2))->where('plant','=', "ADM SAP")
        // ->get();
        // $transactions = Transaction::all();
        // dd($transactions);
        return view('matching.table',['query'=> $results,'transactions'=> $transactions]);
        //
    }
}
