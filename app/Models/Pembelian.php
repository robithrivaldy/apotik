<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id', 'jenis_pembelian_id', 'no_faktur', 'no_faktur_pajak', 'tgl_faktur', 'tgl_jatuh_tempo', 'tgl_diterima', 'keterangan', 'total', 'created_by', 'updated_by', 'created_at', 'updated_at'];


    public function obat()
    {
        return $this->hasMany(Obat::class);
    }

    public function masterObat()
    {
        return $this->hasMany(MasterObat::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function jenisPembelian()
    {
        return $this->belongsTo(JenisPembelian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
