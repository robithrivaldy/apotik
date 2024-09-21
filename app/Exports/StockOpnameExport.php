<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Penjualan;
use App\Models\Setting;
use App\Models\StockOpname;

class StockOpnameExport implements FromView
{
    public function __construct(protected readonly string $dari,protected readonly string $sampai) {

    }

    public function view(): View
    {
        $data = StockOpname::with('obatColumn.masterObat')->whereBetween('created_at',[$this->dari,$this->sampai])->get();
        $apotik = Setting::find(1);
        $dari = $this->dari;
        $sampai = $this->sampai;

        // dd($data);
        // dd($data);
        return view('export_stock_opname',compact('data','apotik','dari','sampai'));
    }
}
