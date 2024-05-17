<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    protected $table = 'schedule';
    public $guarded = [];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }
}
