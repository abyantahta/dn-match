<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\DnADM;
use App\Models\Dashboard;
use App\Models\DnADMKEP;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class DnADMKEPImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    // /**
    //  * Tentukan baris awal data
    //  */
    // public function headingRow(): int
    // {
    //     return 4; // Mulai membaca dari baris ke-5
    // }


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        if ($row[14] !== 'D102') {
            return new DnADM([
                // 'plant_code' => 1234
                // 'plant'
            ]);
        }
        // $dataDashboard = new Dashboard;
        // $dataDashboard->del_cycle = $row[17];
        // $dataDashboard->no_dn = $row[10];
        // $dataDashboard->no_job = $row[25];
        // $dataDashboard->kanban_match = '0/' . $row[28];
        // $dataDashboard->dn_status = 'OPEN';
        // $dataDashboard->created_at = Carbon::now()->toDateString();
        // $dataDashboard->save();
        // dd($row);
        return new DnADMKEP([
            'dn_no' => $row[0],
            'vendor_no' => $row[1],
            'vendor_alias' => $row[2],
            'vendor_site' => $row[3],
            'vendor_site_alias' => $row[4],
            'trans_code' => $row[5],
            'trans_route' => $row[6],
            'anbunka_date' => Carbon::parse($row[7]),
            'anbunka_shift' => $row[8],
            'order_no' => $row[9],
            'order_date' => Carbon::parse($row[10]),
            'order_time' => Carbon::parse($row[11]),
            'order_cycle' => $row[12],
            'shop_code' => $row[13],
            'plant_code' => $row[14],
            'part_cat' => $row[15],
            'wh_no' => $row[16],
            'del_type' => $row[17],
            'del_date' => Carbon::parse($row[18]),
            'del_time' => Carbon::parse($row[19]),
            'del_cycle' => $row[20],
            'dock_no' => $row[21],
            'park_no' => $row[22],
            'total_kanban' => $row[23],
            'printed' => $row[24],
            'receive_status' => $row[25],
            'dn_type' => $row[26],
            'receive_method' => $row[27],
            'receive_date' => Carbon::parse($row[28]),
            'receive_by' => $row[29],
            'status' => $row[30],
            'no' => $row[31],
            'ext_core' => $row[32],
            'part_no' => $row[33],
            'part_name' => $row[34],
            'job_no' => $row[35],
            'qty_box' => $row[36],
            'qty_kanban' => $row[37],
            'qty_order' => $row[38],
            'qty_recieve' => $row[39],
            'qty_balance' => $row[40],
            'partial_status' => $row[41],
            'updated_date' => Carbon::parse($row[42]),
            'disable_date' => Carbon::parse($row[43]),
            'order_status' => $row[44],
            'dn_job_no' => $row[0] . $row[33] . str_pad($row[37], 3, '0', STR_PAD_LEFT),
        ]);
    }

    // /**
    //  * Tentukan baris awal data
    //  */
    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris ke-5
    }
}
