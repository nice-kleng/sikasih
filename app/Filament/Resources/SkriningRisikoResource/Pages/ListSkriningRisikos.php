<?php

namespace App\Filament\Resources\SkriningRisikoResource\Pages;

use App\Filament\Resources\SkriningRisikoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSkriningRisikos extends ListRecords
{
    protected static string $resource = SkriningRisikoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
