<?php

namespace App\Imports;

use App\Models\Demo;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class DemosImport implements ToModel, WithStartRow, WithProgressBar
{

    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Demo([
            //
            'name'     => $row[0],
            'email'    => $row[1],
            'name_email'    => $row[0] . $row[1],
            'password' => Hash::make($row[2]),
        ]);
    }

    // /**
    //  * Write code on Method
    //  *
    //  * @return response()
    //  */
    // public function rules(): array
    // {
    //     return [
    //         'name' => 'required',
    //         'password' => 'required|min:5',
    //         'email' => 'required|email|unique:users'
    //     ];
    // }


    // public function headingRow(): int
    // {
    //     return 4;
    // }
    // /**
    //  * Tentukan baris awal data
    //  */
    public function startRow(): int
    {
        return 5; // Mulai membaca dari baris ke-5
    }
}
