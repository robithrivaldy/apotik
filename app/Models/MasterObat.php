<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterObat extends Model
{
    use HasFactory;
    protected $fillable = ['name',  'sediaan_id', 'satuan_id', 'pt_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];



    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }

    public function pt()
    {
        return $this->belongsTo(Pt::class, 'pt_id', 'id');
    }

    public function obat()
    {
        return $this->hasMany(Obat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function sediaan()
    {
        return $this->belongsTo(Sediaan::class, 'sediaan_id', 'id');
    }
}
