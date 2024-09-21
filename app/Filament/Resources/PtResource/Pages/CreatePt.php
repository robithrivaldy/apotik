<?php

namespace App\Filament\Resources\PtResource\Pages;

use App\Filament\Resources\PtResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePt extends CreateRecord
{
    protected static string $resource = PtResource::class;

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
