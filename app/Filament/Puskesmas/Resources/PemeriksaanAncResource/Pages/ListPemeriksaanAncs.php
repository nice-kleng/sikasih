<?php

namespace App\Filament\Puskesmas\Resources\PemeriksaanAncResource\Pages;

use App\Filament\Puskesmas\Resources\PemeriksaanAncResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemeriksaanAncs extends ListRecords
{
    protected static string $resource = PemeriksaanAncResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
