<?php

namespace App\Filament\Resources\SediaanResource\Pages;

use App\Filament\Resources\SediaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSediaan extends EditRecord
{
    protected static string $resource = SediaanResource::class;

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
