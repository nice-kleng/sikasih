<?php

namespace App\Filament\Resources\SkriningRisikoResource\Pages;

use App\Filament\Resources\SkriningRisikoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSkriningRisiko extends ViewRecord
{
    protected static string $resource = SkriningRisikoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
