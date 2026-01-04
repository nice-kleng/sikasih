<?php

namespace App\Filament\Resources\HasilLaboratoriumResource\Pages;

use App\Filament\Resources\HasilLaboratoriumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHasilLaboratoria extends ListRecords
{
    protected static string $resource = HasilLaboratoriumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
