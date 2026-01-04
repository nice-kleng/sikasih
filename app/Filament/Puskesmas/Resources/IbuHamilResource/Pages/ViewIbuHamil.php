<?php

namespace App\Filament\Puskesmas\Resources\IbuHamilResource\Pages;

use App\Filament\Puskesmas\Resources\IbuHamilResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIbuHamil extends ViewRecord
{
    protected static string $resource = IbuHamilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
