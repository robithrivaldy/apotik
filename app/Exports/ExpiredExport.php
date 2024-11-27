<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Pembelian;
use App\Models\Setting;
use App\Models\Obat;
class ExpiredExport implements FromView
{
    public function __construct(protected readonly string $dari,protected readonly string $sampai) {

    }

    public function view(): View
    {
        $dari = $this->dari;
        $sampai = $this->sampai;
        $data = Obat::whereBetween('tgl_expired',[$dari,$sampai])->get();
        $apotik = Setting::find(1);

        // dd($dari);
        // $total = $data->obat->sum('pembelian_total');

        // dd($total);
        return view('export_expired',compact('data','apotik','dari','sampai'));
    }
}
