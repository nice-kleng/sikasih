<?php

namespace App\Filament\Resources\SkriningRisikoResource\Pages;

use App\Filament\Resources\SkriningRisikoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkriningRisiko extends EditRecord
{
    protected static string $resource = SkriningRisikoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
