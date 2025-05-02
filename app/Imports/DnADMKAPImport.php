<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\DnADM;
use App\Models\Dashboard;
use App\Models\DnADMKAP;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class DnADMKAPImport implements ToModel, WithStartRow
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
        if ($row[0] !== 'D105') {
            return new DnADM([
                // 'plant_code' => 1234
                // 'plant'
            ]);
        }

        return new DnADMKAP([
            'plant_code' => $row[0],
            'shop_code' => $row[1],
            'part_category' => $row[2],
            'route' => $row[3],
            'lp' => $row[4],
            'trip' => $row[5],
            'vendor_code' => $row[6],
            'vendor_alias' => $row[7],
            'vendor_site' => $row[8],
            'vendor_site_alias' => $row[9],
            'order_no' => $row[10],
            'po_number' => $row[11],
            'calc_date' => \Carbon\Carbon::parse($row[12]),
            'order_date' => \Carbon\Carbon::parse($row[13]),
            'order_time' => $row[14],
            'del_date' => \Carbon\Carbon::parse($row[15]),
            'del_time' => $row[16],
            'del_cycle' => $row[17],
            'doc_no' => $row[18],
            'rec_status' => $row[19],
            'dn_type' => $row[20],
            'rec_date' => \Carbon\Carbon::parse($row[21]),
            'rec_by' => $row[22],
            'part_no' => $row[23],
            'part_name' => $row[24],
            'job_no' => $row[25],
            'lane' => $row[26],
            'qty_kbn' => $row[27],
            'order_kbn' => $row[28],
            'order_pcs' => $row[29],
            'qty_receive' => $row[30],
            'qty_balance' => $row[31],
            'cancel_status' => $row[32],
            'remark' => $row[33],
            'dn_job_no' => $row[10] . $row[25] . str_pad($row[28], 3, '0', STR_PAD_LEFT),
        ]);
    }

    // /**
    //  * Tentukan baris awal data
    //  */
    public function startRow(): int
    {
        return 5; // Mulai membaca dari baris ke-5
    }
}
