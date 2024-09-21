<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use App\Models\Setting;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Barryvdh\DomPDF\Facade\Options;

class PrintPenjualanController extends Controller
{
    public function detail($id)
    {
        $data_penjualan_item = PenjualanItem::where('penjualan_id', '=', $id)->get();
        $data_penjualan = Penjualan::find($id);
        $data_apotik = Setting::find(1);


        $pdf = PDF::loadView('nota_thermal', compact('data_penjualan_item', 'data_penjualan', 'data_apotik'));
        $pdf->render();

        return $pdf->stream('laporan.pdf');
    }
}
