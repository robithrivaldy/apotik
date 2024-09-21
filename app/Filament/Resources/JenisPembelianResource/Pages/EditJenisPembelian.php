<?php

namespace App\Filament\Resources\JenisPembelianResource\Pages;

use App\Filament\Resources\JenisPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisPembelian extends EditRecord
{

    protected static string $resource = JenisPembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        $data['updated_by'] = auth()->id();
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
