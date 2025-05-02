<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnADMKAP extends Model
{
    /** @use HasFactory<\Database\Factories\DnADMKAPFactory> */
    use HasFactory;
    protected $table = 'dnadmkap';
    protected $guarded = [];
}
