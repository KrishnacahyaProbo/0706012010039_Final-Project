<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuDetail extends Model
{
    use HasFactory;

    protected $table = 'menu_detail';
    public $guarded = [];
}
