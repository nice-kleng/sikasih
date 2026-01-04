<?php

namespace App\Filament\Resources\TenagaKesehatanResource\Pages;

use App\Filament\Resources\TenagaKesehatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTenagaKesehatan extends ViewRecord
{
    protected static string $resource = TenagaKesehatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
