<?php

namespace App\Imports;

use App\Models\OrderDelivery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;


class OrderDeliveryImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    /**
     * Tentukan baris awal data
     */
    public function startRow(): int
    {
        return 5; // Mulai membaca dari baris ke-5
    }

    /**
     * Map baris menjadi model OrderDelivery
     */
    public function model(array $row)
    {
        return new OrderDelivery([
            'plant_code' => $row['plant_code'],
            'shop_code' => $row['shop_code'],
            'part_category' => $row['part_category'],
            'route' => $row['route'],
            'lp' => $row['lp'],
            'trip' => $row['trip'],
            'vendor_code' => $row['vendor_code'],
            'vendor_alias' => $row['vendor_alias'],
            'vendor_site' => $row['vendor_site'],
            'vendor_site_alias' => $row['vendor_site_alias'],
            'order_no' => $row['order_no'],
            'po_number' => $row['po_number'],
            'calc_date' => \Carbon\Carbon::parse($row['calc_date']),
            'order_date' => \Carbon\Carbon::parse($row['order_date']),
            'order_time' => $row['order_time'],
            'del_date' => \Carbon\Carbon::parse($row['del_date']),
            'del_time' => $row['del_time'],
            'del_cycle' => $row['del_cycle'],
            'doc_no' => $row['doc_no'],
            'rec_status' => $row['rec_status'],
            'dn_type' => $row['dn_type'],
            'rec_date' => \Carbon\Carbon::parse($row['rec_date']),
            'rec_by' => $row['rec_by'],
            'part_no' => $row['part_no'],
            'part_name' => $row['part_name'],
            'job_no' => $row['job_no'],
            'lane' => $row['lane'],
            'qty_kbn' => $row['qty_kbn'],
            'order_kbn' => $row['order_kbn'],
            'order_pcs' => $row['order_pcs'],
            'qty_receive' => $row['qty_receive'],
            'qty_balance' => $row['qty_balance'],
            'cancel_status' => $row['cancel_status'],
            'remark' => $row['remark'],
        ]);
    }
}
