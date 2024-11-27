<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Notifications\Actions\Action;
use App\Models\Obat;

class CreateStockOpname extends CreateRecord
{
    protected static string $resource = StockOpnameResource::class;

    protected function afterCreate(): void
    {
        $data = $this->record;

        Obat::find($data->obat_id)->update(['stock' => $data->stock_akhir]);
    }

    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
            ->submit(null)
            ->requiresConfirmation()
            ->action(function () {
                $this->closeActionModal();
                $this->create();
            });
    }
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
