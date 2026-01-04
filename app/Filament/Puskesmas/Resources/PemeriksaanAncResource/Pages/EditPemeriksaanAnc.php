<?php

namespace App\Filament\Puskesmas\Resources\PemeriksaanAncResource\Pages;

use App\Filament\Puskesmas\Resources\PemeriksaanAncResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemeriksaanAnc extends EditRecord
{
    protected static string $resource = PemeriksaanAncResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
