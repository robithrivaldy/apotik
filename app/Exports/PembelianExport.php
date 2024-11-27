<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Pembelian;
use App\Models\Setting;

class PembelianExport implements FromView
{
    public function __construct(protected readonly string $dari,protected readonly string $sampai) {

    }

    public function view(): View
    {
        $data = Pembelian::with('obat')->whereBetween('tgl_jatuh_tempo',[$this->dari,$this->sampai])->get();
        $apotik = Setting::find(1);
        $dari = $this->dari;
        $sampai = $this->sampai;
        // dd($dari);
        // $total = $data->obat->sum('pembelian_total');

        // dd($total);
        return view('export_pembelian',compact('data','apotik','dari','sampai'));
    }
}
