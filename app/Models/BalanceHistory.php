<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BalanceHistory extends Model
{
    use HasFactory;

    protected $table = 'balance_history';
    public $guarded = ['id'];
    public $timestamps = true;
}
