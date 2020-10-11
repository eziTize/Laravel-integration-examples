<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferwise extends Model
{
    use HasFactory;

    protected $table = "transferwises";
        protected $fillable = [
             'name', 'ac_type', 'ac_no', 'amount'
        ];
}
