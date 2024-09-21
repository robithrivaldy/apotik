<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use Illuminate\Support\Facades\Redirect;
use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Obat;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getCreateFormAction(): Actions\Action
    {
        // $record = $this->record;
        return parent::getCreateFormAction()
            ->submit(null)
            ->requiresConfirmation()
            ->action(function () {

                $this->closeActionModal();
                $this->create();
            });
    }

    // protected function getRedirectUrl(): string
    // {
    //     return static::getResource()::getUrl('pdf');
    // }

    protected function afterCreate(): void
    {
        $data = $this->record;
        $record = $this->record;

        $collect = collect($data->penjualanItem)->filter(fn($item) => !empty($item['obat_id']) && !empty($item['qty']));


        $stock = Obat::find($collect->pluck('obat_id'))->pluck('stock', 'id');


        $stocks = $collect->reduce(function ($stocks, $product) use ($stock) {
            $stock_akhir = $stock[$product['obat_id']] - $product['qty'];
            Obat::find($product['obat_id'])->update(['stock' => $stock_akhir]);
            return $stocks + 1;
        }, 0);
        // dd($stocks);
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        return $data;
    }
}
