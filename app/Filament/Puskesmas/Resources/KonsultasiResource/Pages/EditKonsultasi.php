<?php

namespace App\Filament\Puskesmas\Resources\KonsultasiResource\Pages;

use App\Filament\Puskesmas\Resources\KonsultasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKonsultasi extends EditRecord
{
    protected static string $resource = KonsultasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
