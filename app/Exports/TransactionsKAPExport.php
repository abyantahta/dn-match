<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionsKAPExport implements FromView
{
    public static $plant;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view():View
    {
        $transactions = Transaction::whereDate('created_at', '>', now()->subDays(2))->where('plant','=', "ADM KAP")
        ->get();
        // dd($transactions);
        return view('matching.table',['transactions'=> $transactions]);
        //
    }
}
