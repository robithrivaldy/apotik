<?php

namespace App\Filament\Resources\PtResource\Pages;

use App\Filament\Resources\PtResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPt extends EditRecord
{
    protected static string $resource = PtResource::class;

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
