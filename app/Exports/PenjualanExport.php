<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Penjualan;
use App\Models\Setting;

class PenjualanExport implements FromView
{
    public function __construct(protected readonly string $dari,protected readonly string $sampai,protected readonly string $user) {

    }

    public function view(): View
    {

        if($this->user == '0'){
            $data = Penjualan::with('items.obat.masterObat')->whereBetween('created_at',[$this->dari,$this->sampai])->get();
        }else{
            $data = Penjualan::with('items.obat.masterObat')->whereBetween('created_at',[$this->dari,$this->sampai])->where('created_by','=',$this->user)->get();
        }

        $apotik = Setting::find(1);
        $dari = $this->dari;
        $sampai = $this->sampai;

        // dd($data);
        // dd($data);
        return view('export_penjualan',compact('data','apotik','dari','sampai'));
    }
}
