<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\PenjualanExport;
use Filament\Forms\Components\DatePicker;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('Excel')
            ->label('Excel')
            ->color('success')
            ->icon('heroicon-o-printer')
            ->action(fn($record,array $data) =>   Excel::download(new PenjualanExport($data['dari'],$data['sampai'],$data['user'] ?? '0' ), 'penjualan.xlsx'))
            ->form([
                DatePicker::make('dari'),
                DatePicker::make('sampai'),
                Select::make('user')
                     ->options([
                        'draft' => 'Draft'
                    ])
                    ->relationship(
                    name: 'user',
                    titleAttribute: 'name',
                )
            ]),


        ];
    }
}
