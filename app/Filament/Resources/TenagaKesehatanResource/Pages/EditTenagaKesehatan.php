<?php

namespace App\Filament\Resources\TenagaKesehatanResource\Pages;

use App\Filament\Resources\TenagaKesehatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenagaKesehatan extends EditRecord
{
    protected static string $resource = TenagaKesehatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
