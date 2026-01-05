<?php

namespace App\Filament\Puskesmas\Widgets;

use App\Models\PemeriksaanAnc;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPemeriksaan extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = auth()->user();
        $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;

        return $table
            ->heading('Pemeriksaan Terbaru')
            ->query(
                PemeriksaanAnc::query()
                    ->where('puskesmas_id', $puskesmasId)
                    ->with(['ibuHamil', 'tenagaKesehatan.user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_pemeriksaan')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_pemeriksaan')
                    ->label('No.')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ibuHamil.nama_lengkap')
                    ->label('Ibu Hamil')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('kunjungan_ke')
                    ->label('ANC')
                    ->formatStateUsing(fn($state) => "Ke-{$state}")
                    ->color('primary'),
                Tables\Columns\TextColumn::make('tekanan_darah')
                    ->label('TD')
                    ->getStateUsing(fn(PemeriksaanAnc $record) => $record->tekanan_darah)
                    ->badge()
                    ->color(fn(PemeriksaanAnc $record) => match ($record->statusTekananDarah) {
                        'tinggi' => 'danger',
                        'rendah' => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('tenagaKesehatan.user.nama')
                    ->label('Pemeriksa')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn(PemeriksaanAnc $record) => route('filament.puskesmas.resources.pemeriksaan-ancs.view', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5]);
    }
}
