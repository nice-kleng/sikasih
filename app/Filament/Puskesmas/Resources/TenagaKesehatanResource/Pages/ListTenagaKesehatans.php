<?php

namespace App\Filament\Puskesmas\Resources\TenagaKesehatanResource\Pages;

use App\Filament\Puskesmas\Resources\TenagaKesehatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenagaKesehatans extends ListRecords
{
    protected static string $resource = TenagaKesehatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
