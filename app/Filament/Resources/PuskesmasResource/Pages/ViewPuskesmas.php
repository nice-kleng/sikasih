<?php

namespace App\Filament\Resources\PuskesmasResource\Pages;

use App\Filament\Resources\PuskesmasResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPuskesmas extends ViewRecord
{
    protected static string $resource = PuskesmasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
