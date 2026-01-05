<?php

namespace App\Filament\Widgets;

use App\Models\PemeriksaanAnc;
use App\Models\IbuHamil;
use App\Models\SkriningRisiko;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivities extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Aktivitas Terbaru')
            ->query(
                PemeriksaanAnc::query()
                    ->with(['ibuHamil', 'puskesmas', 'tenagaKesehatan.user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_pemeriksaan')
                    ->label('No. Pemeriksaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ibuHamil.nama_lengkap')
                    ->label('Ibu Hamil')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('puskesmas.nama_puskesmas')
                    ->label('Puskesmas')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('tenagaKesehatan.user.nama')
                    ->label('Pemeriksa')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('kunjungan_ke')
                    ->label('ANC')
                    ->formatStateUsing(fn($state) => "Ke-{$state}")
                    ->color('primary'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5]);
    }
}
