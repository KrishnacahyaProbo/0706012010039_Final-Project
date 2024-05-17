<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsToMany(Schedule::class)
            ->withPivot('id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }
}
