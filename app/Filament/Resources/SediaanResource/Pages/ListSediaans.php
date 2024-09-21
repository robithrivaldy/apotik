<?php

namespace App\Filament\Resources\SediaanResource\Pages;

use App\Filament\Resources\SediaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSediaans extends ListRecords
{
    protected static string $resource = SediaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
