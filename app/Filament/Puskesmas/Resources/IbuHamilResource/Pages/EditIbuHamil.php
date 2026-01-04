<?php

namespace App\Filament\Puskesmas\Resources\IbuHamilResource\Pages;

use App\Filament\Puskesmas\Resources\IbuHamilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIbuHamil extends EditRecord
{
    protected static string $resource = IbuHamilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
