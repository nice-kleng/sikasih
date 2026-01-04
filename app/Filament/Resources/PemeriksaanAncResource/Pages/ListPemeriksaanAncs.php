<?php

namespace App\Filament\Resources\PemeriksaanAncResource\Pages;

use App\Filament\Resources\PemeriksaanAncResource;
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
