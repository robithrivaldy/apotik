<?php

namespace App\Filament\Resources\PtResource\Pages;

use App\Filament\Resources\PtResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPts extends ListRecords
{
    protected static string $resource = PtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
