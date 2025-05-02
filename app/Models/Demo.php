<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

class Demo extends Model
{
    //
    use HasFactory;
    protected $table = "demos";
    protected $fillable = ['name', 'email', 'password', 'name_email'];
}
