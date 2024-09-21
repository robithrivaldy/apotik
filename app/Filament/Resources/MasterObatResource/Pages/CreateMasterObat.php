<?php

namespace App\Filament\Resources\MasterObatResource\Pages;

use App\Filament\Resources\MasterObatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterObat extends CreateRecord
{
    protected static string $resource = MasterObatResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
