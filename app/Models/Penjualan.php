<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'customer', 'subtotal', 'discount', 'total',  'keterangan', 'created_by',  'updated_by', 'created_at', 'updated_at'];

    public function obat()
    {
        return $this->hasMany(Obat::class)->where('stock', '=', 0);;
    }

    public function penjualan()
    {
        return $this->hasMany(PenjualanItem::class, "penjualan_id", "id");
    }

    public function PenjualanItem()
    {
        return $this->hasMany(PenjualanItem::class, 'penjualan_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(PenjualanItem::class, 'penjualan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function JenisPembelian()
    {
        return $this->belongsTo(JenisPembelian::class);
    }
}
