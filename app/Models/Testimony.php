<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    use HasFactory;

    protected $table = 'testimonies';
    public $guarded = [];
    public $timestamps = true;
    protected $with = ['vendor', 'customer'];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
