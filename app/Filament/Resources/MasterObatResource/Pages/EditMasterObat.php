<?php

namespace App\Filament\Resources\MasterObatResource\Pages;

use App\Filament\Resources\MasterObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterObat extends EditRecord
{
    protected static string $resource = MasterObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
