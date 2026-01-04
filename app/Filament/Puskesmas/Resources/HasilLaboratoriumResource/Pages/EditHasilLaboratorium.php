<?php

namespace App\Filament\Puskesmas\Resources\HasilLaboratoriumResource\Pages;

use App\Filament\Puskesmas\Resources\HasilLaboratoriumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHasilLaboratorium extends EditRecord
{
    protected static string $resource = HasilLaboratoriumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
