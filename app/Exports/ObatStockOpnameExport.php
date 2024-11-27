<?php

namespace App\Exports;

use App\Invoice;
use App\Models\Obat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Penjualan;
use App\Models\Setting;
use App\Models\StockOpname;

class ObatStockOpnameExport implements FromView
{
    public function __construct(protected readonly string $dari, protected readonly string $sampai) {}

    public function view(): View
    {
        $data = Obat::where('stock', '<>', 0)->whereBetween('created_at', [$this->dari, $this->sampai])->get();
        $apotik = Setting::find(1);
        $dari = $this->dari;
        $sampai = $this->sampai;

        // dd($data);
        // dd($data);
        return view('export_obat_stock_opname', compact('data', 'apotik', 'dari', 'sampai'));
    }
}
