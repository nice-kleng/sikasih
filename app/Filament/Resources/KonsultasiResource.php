<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KonsultasiResource\Pages;
use App\Filament\Resources\KonsultasiResource\RelationManagers;
use App\Models\Konsultasi;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KonsultasiResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Konsultasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ibu_hamil_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tenaga_kesehatan_id')
                    ->numeric(),
                Forms\Components\TextInput::make('puskesmas_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('no_konsultasi')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_konsultasi')
                    ->required(),
                Forms\Components\TextInput::make('waktu_mulai')
                    ->required(),
                Forms\Components\TextInput::make('waktu_selesai'),
                Forms\Components\TextInput::make('durasi_menit')
                    ->numeric(),
                Forms\Components\TextInput::make('usia_kehamilan_minggu')
                    ->numeric(),
                Forms\Components\TextInput::make('topik_konsultasi')
                    ->required(),
                Forms\Components\TextInput::make('kategori')
                    ->required(),
                Forms\Components\TextInput::make('metode')
                    ->required(),
                Forms\Components\Textarea::make('keluhan_ibu')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('jawaban_konsultasi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('saran')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('tindak_lanjut')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('prioritas')
                    ->required(),
                Forms\Components\TextInput::make('rating')
                    ->numeric(),
                Forms\Components\Textarea::make('feedback')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ibu_hamil_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tenaga_kesehatan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('puskesmas_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_konsultasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_konsultasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_mulai'),
                Tables\Columns\TextColumn::make('waktu_selesai'),
                Tables\Columns\TextColumn::make('durasi_menit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usia_kehamilan_minggu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('topik_konsultasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prioritas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKonsultasis::route('/'),
            'create' => Pages\CreateKonsultasi::route('/create'),
            'edit' => Pages\EditKonsultasi::route('/{record}/edit'),
        ];
    }
}
