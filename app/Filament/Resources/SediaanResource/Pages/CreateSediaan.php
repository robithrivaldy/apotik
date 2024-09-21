<?php

namespace App\Filament\Resources\SediaanResource\Pages;

use App\Filament\Resources\SediaanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSediaan extends CreateRecord
{
    protected static string $resource = SediaanResource::class;
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
