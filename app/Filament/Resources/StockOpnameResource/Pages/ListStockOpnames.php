<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\PembelianExport;
use App\Exports\StockOpnameExport;
use Filament\Forms\Components\DatePicker;
use Maatwebsite\Excel\Facades\Excel;

class ListStockOpnames extends ListRecords
{
    protected static string $resource = StockOpnameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Export')
            ->label('Export')
            ->color('success')
            ->icon('heroicon-o-printer')
            ->action(fn($record,array $data) =>   Excel::download(new StockOpnameExport($data['dari'],$data['sampai']), 'pembelian.xlsx'))
            ->form([
                DatePicker::make('dari'),
                DatePicker::make('sampai')
            ]),
        ];
    }
}
