<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceNominal extends Model
{
    use HasFactory;

    protected $table = 'balance_nominal';
    public $guarded = ['id'];
}
