<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\PembelianExport;
use Filament\Forms\Components\DatePicker;
use Maatwebsite\Excel\Facades\Excel;
class ListPembelians extends ListRecords
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Export')
            ->label('Export')
            ->color('success')
            ->icon('heroicon-o-printer')
            ->action(fn($record,array $data) =>   Excel::download(new PembelianExport($data['dari'],$data['sampai']), 'pembelian.xlsx'))
            ->form([
                DatePicker::make('dari'),
                DatePicker::make('sampai')
            ]),


        ];
    }
}
