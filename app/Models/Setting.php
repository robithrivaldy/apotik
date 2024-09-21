<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'phone', 'keterangan', 'created_by', 'updated_by', 'created_at', 'updated_at'];
    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
