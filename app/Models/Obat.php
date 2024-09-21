<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $fillable = ['master_obat_id', 'pembelians_id', 'no_batch', 'sediaan_id', 'satuan_id', 'pt_id', 'price', 'stock', 'margin', 'pembelian_price', 'pembelian_stock', 'pembelian_total', 'tgl_expired', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public function masterObat()
    {
        return $this->belongsTo(MasterObat::class);
    }
    public function obat()
    {
        return $this->belongsTo(MasterObat::class);
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function pt()
    {
        return $this->belongsTo(Pt::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function Sediaan()
    {
        return $this->belongsTo(Sediaan::class);
    }
}
