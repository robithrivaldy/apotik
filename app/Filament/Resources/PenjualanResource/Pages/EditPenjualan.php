<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\ObatResource;
use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Redirect;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class EditPenjualan extends EditRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('cetak')
                ->label('Cetak')
                ->color('success')
                ->icon('heroicon-o-printer')
                ->requiresConfirmation(true)
                // ->action(fn() => Redirect::away('pdf'))
                ->openUrlInNewTab()
                ->url(fn($record) => route('print.penjualan.detail', $this->record->id))
                ->openUrlInNewTab(),
        ];
    }

    // protected function getRedirectUrl(): string
    // {
    //     return ObatResource::getUrl('index');
    // }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        $data['updated_by'] = auth()->id();
        return $data;
    }
}
