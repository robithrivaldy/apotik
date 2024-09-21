<?php

namespace App\Filament\Resources\JenisPembelianResource\Pages;

use App\Filament\Resources\JenisPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisPembelian extends CreateRecord
{
    protected static string $resource = JenisPembelianResource::class;

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
