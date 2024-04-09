<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';
    public $guarded = [];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }
}
