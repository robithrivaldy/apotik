<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use App\Models\Pembelian;
use App\Models\Setting;
use App\Models\Obat;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Exports\PembelianExport;

use Maatwebsite\Excel\Facades\Excel;

class PrintPembelianController extends Controller
{
    // public function index($id)
    // {
    //     return Excel::download(new PembelianExport, 'invoices.xlsx');
    // }

    public function detail($id)
    {
        // $pdf = PDF::loadHtml(Blade::render('pdf'));
        // $pdf->render();

        // return $pdf->stream('laporan.pdf');

        $data = Obat::where('pembelian_id', '=', $id)->get();
        $data_pembelian = Pembelian::find($id);
        $data_apotik = Setting::find(1);

        // dd($data_pembelian);
        // $data = Penjualan::all();
        // $customer = new Buyer([
        //     'name'          => $data_apotik->id,
        //     'custom_fields' => [
        //         'email' => 'test@example.com',
        //     ],
        // ]);

        $client = new Party([
            'name'          => $data_pembelian->supplier->name,
            // 'phone'         => '(520) 318-9486',
            'custom_fields' => [
                'No Faktur'        => $data_pembelian->no_faktur,
                'Tgl Pajak' => date('d, M Y', strtotime($data_pembelian->tgl_faktur)),
                'Tgl Jatuh Tempo' => date('d, M Y', strtotime($data_pembelian->tgl_jatuh_tempo)),

            ],
        ]);

        $customer = new Party([
            'name'          => $data_apotik->name,
            'address'       =>  $data_apotik->address,
            // 'phone'          => '#22663214',
            'custom_fields' => [
                'Diterima Oleh' =>  $data_pembelian->user->name,
                'Pada' =>  date('d, M Y', strtotime($data_pembelian->tgl_diterima)),
            ],
        ]);


        // $items = [];
        foreach ($data as $value) {
            // dd($value->penjualanItem);

            $items[] = InvoiceItem::make($value->masterObat->name)
                ->description('No.Batch: ' . $value->no_batch . '(Expired: ' . date('d, M Y', strtotime($value->tgl_expired)) . ')')
                ->satuan($value->masterObat->satuan->name)
                ->sediaan($value->masterObat->sediaan->name)
                ->pricePerUnit($value->pembelian_price)
                ->pt($value->masterObat->pt->name)
                ->quantity($value->pembelian_stock);
        }


        $notes = [
            $data_pembelian->keterangan,
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make()
            // ->series('BIG')

            // ->discountByPercent($data_pembelian['discount'])
            // ->taxRate(15)
            // ->shipping(1.99)
            ->seller($client)
            ->buyer($customer)
            ->series(1111)
            ->serialNumberFormat('{SERIES}')
            ->notes($notes)
            ->status($data_pembelian->jenisPembelian->name)
            ->addItems($items);


        return $invoice->stream();
    }
}
