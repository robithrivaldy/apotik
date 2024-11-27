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

        $c_item = count($data_penjualan_item);

        // dd($c_item);
        if($c_item < 5 ){
            $docHeight = 200 + ($c_item * 100);
        }else{
            $docHeight =  ($c_item * 100) + 100;
        }



        $pdf = PDF::loadView('nota_thermal', compact('data_penjualan_item', 'data_penjualan', 'data_apotik'))->setPaper([0,0,250, $docHeight]);
        $pdf->render();

        return $pdf->stream('laporan.pdf');
    }
}
