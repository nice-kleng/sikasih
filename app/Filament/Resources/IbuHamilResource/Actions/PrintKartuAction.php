<?php

namespace App\Filament\Resources\IbuHamilResource\Actions;

use App\Models\IbuHamil;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintKartuAction
{
    public static function make(): Action
    {
        return Action::make('print_kartu')
            ->label('Print Kartu ANC')
            ->icon('heroicon-o-printer')
            ->color('success')
            ->action(function (IbuHamil $record) {
                $pdf = Pdf::loadView('pdf.kartu-anc', [
                    'ibuHamil' => $record,
                    'puskesmas' => $record->puskesmas,
                ]);

                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->output();
                }, "kartu-anc-{$record->no_rm}.pdf");
            })
            ->requiresConfirmation()
            ->modalHeading('Print Kartu ANC')
            ->modalDescription('Kartu ANC akan di-download dalam format PDF')
            ->modalSubmitActionLabel('Print');
    }
}
