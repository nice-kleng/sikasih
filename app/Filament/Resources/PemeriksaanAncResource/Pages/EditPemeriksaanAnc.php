<?php

namespace App\Filament\Resources\PemeriksaanAncResource\Pages;

use App\Filament\Resources\PemeriksaanAncResource;
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
