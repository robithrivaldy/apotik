<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanItem extends Model
{
    use HasFactory;
    protected $fillable = ['obat_id', 'penjualan_id', 'price', 'qty',  'total', 'created_by',  'updated_by', 'created_at',  'updated_at'];

    public function obat()
    {
        return $this->hasOne(Obat::class, 'id', 'obat_id');
    }
    public function obats()
    {
        return $this->belongsTo(Obat::class, 'id', 'obat_id');
    }


    public function Penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}
