<?php

namespace App\Filament\Resources\JenisPembelianResource\Pages;

use App\Filament\Resources\JenisPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisPembelians extends ListRecords
{
    protected static string $resource = JenisPembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
