<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';
    public $guarded = [];

    public function menuDetail()
    {
        return $this->hasMany(MenuDetail::class, 'menu_id', 'id');
    }

    public function menu_schedule()
    {
        return $this->belongsToMany(Schedule::class);
    }
}
