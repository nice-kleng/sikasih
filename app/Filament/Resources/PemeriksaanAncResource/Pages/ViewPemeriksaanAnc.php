<?php

namespace App\Filament\Resources\PemeriksaanAncResource\Pages;

use App\Filament\Resources\PemeriksaanAncResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPemeriksaanAnc extends ViewRecord
{
    protected static string $resource = PemeriksaanAncResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
