<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;
    protected $fillable = ['obat_id', 'stock_awal', 'stock_akhir',  'keterangan', 'created_by',  'updated_by', 'created_at', 'updated_at'];

    public function obat()
    {
        return $this->hasMany(Obat::class);
    }

    public function obatColumn()
    {
        return $this->hasOne(Obat::class,'id','obat_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
