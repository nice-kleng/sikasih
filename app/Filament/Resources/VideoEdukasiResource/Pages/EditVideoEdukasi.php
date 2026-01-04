<?php

namespace App\Filament\Resources\VideoEdukasiResource\Pages;

use App\Filament\Resources\VideoEdukasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoEdukasi extends EditRecord
{
    protected static string $resource = VideoEdukasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
