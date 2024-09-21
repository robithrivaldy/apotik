<?php

namespace App\Filament\Resources\MasterObatResource\Pages;

use App\Filament\Resources\MasterObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterObats extends ListRecords
{
    protected static string $resource = MasterObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
