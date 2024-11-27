<?php

namespace App\Filament\Resources\ObatResource\Pages;

use App\Exports\ObatStockOpnameExport;
use App\Filament\Resources\ObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\ExpiredExport;
use Filament\Forms\Components\DatePicker;
use Illuminate\Mail\TextMessage;
use Maatwebsite\Excel\Facades\Excel;

class ListObats extends ListRecords
{
    protected static string $resource = ObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Export Obat Expired')
                ->label('Export Obat Expired')
                ->color('success')
                ->icon('heroicon-o-printer')
                ->action(fn($record, array $data) =>   Excel::download(new ExpiredExport($data['dari'], $data['sampai']), 'expired.xlsx'))
                ->form([
                    DatePicker::make('dari'),
                    DatePicker::make('sampai')
                ]),

            Actions\Action::make('Export Stock Opname')
                ->label('Export Stock Opname')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->action(fn($record, array $data) =>   Excel::download(new ObatStockOpnameExport($data['dari'], $data['sampai']), 'laporanstockopanname.xlsx'))
                ->form([
                    DatePicker::make('dari'),
                    DatePicker::make('sampai')
                ]),
        ];
    }
}
