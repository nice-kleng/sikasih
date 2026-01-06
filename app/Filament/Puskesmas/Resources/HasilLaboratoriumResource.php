<?php

namespace App\Filament\Puskesmas\Resources;

use App\Filament\Puskesmas\Resources\HasilLaboratoriumResource\Pages;
use App\Filament\Puskesmas\Resources\HasilLaboratoriumResource\RelationManagers;
use App\Models\HasilLaboratorium;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasilLaboratoriumResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = HasilLaboratorium::class;

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
                Forms\Components\TextInput::make('puskesmas_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pemeriksaan_anc_id')
                    ->numeric(),
                Forms\Components\TextInput::make('no_lab')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_pemeriksaan')
                    ->required(),
                Forms\Components\TextInput::make('usia_kehamilan_minggu')
                    ->numeric(),
                Forms\Components\TextInput::make('jenis_pemeriksaan')
                    ->required(),
                Forms\Components\TextInput::make('hemoglobin')
                    ->numeric(),
                Forms\Components\TextInput::make('leukosit')
                    ->numeric(),
                Forms\Components\TextInput::make('eritrosit')
                    ->numeric(),
                Forms\Components\TextInput::make('trombosit')
                    ->numeric(),
                Forms\Components\TextInput::make('hematokrit')
                    ->numeric(),
                Forms\Components\TextInput::make('mcv')
                    ->numeric(),
                Forms\Components\TextInput::make('mch')
                    ->numeric(),
                Forms\Components\TextInput::make('mchc')
                    ->numeric(),
                Forms\Components\TextInput::make('golongan_darah'),
                Forms\Components\TextInput::make('rhesus'),
                Forms\Components\TextInput::make('warna_urine'),
                Forms\Components\TextInput::make('kejernihan_urine'),
                Forms\Components\TextInput::make('ph_urine')
                    ->numeric(),
                Forms\Components\TextInput::make('berat_jenis_urine'),
                Forms\Components\TextInput::make('protein_urine'),
                Forms\Components\TextInput::make('glukosa_urine'),
                Forms\Components\TextInput::make('keton_urine'),
                Forms\Components\TextInput::make('bilirubin_urine'),
                Forms\Components\TextInput::make('urobilinogen_urine'),
                Forms\Components\TextInput::make('leukosit_urine')
                    ->numeric(),
                Forms\Components\TextInput::make('eritrosit_urine')
                    ->numeric(),
                Forms\Components\TextInput::make('hbsag'),
                Forms\Components\TextInput::make('anti_hcv'),
                Forms\Components\TextInput::make('anti_hiv'),
                Forms\Components\TextInput::make('vdrl_sifilis'),
                Forms\Components\TextInput::make('tpha_sifilis'),
                Forms\Components\TextInput::make('toxoplasma_igg'),
                Forms\Components\TextInput::make('toxoplasma_igm'),
                Forms\Components\TextInput::make('rubella_igg'),
                Forms\Components\TextInput::make('rubella_igm'),
                Forms\Components\TextInput::make('cmv_igg'),
                Forms\Components\TextInput::make('cmv_igm'),
                Forms\Components\TextInput::make('gula_darah_puasa')
                    ->numeric(),
                Forms\Components\TextInput::make('gula_darah_2jam_pp')
                    ->numeric(),
                Forms\Components\TextInput::make('gula_darah_sewaktu')
                    ->numeric(),
                Forms\Components\TextInput::make('hba1c')
                    ->numeric(),
                Forms\Components\TextInput::make('ureum')
                    ->numeric(),
                Forms\Components\TextInput::make('kreatinin')
                    ->numeric(),
                Forms\Components\TextInput::make('asam_urat')
                    ->numeric(),
                Forms\Components\TextInput::make('sgot')
                    ->numeric(),
                Forms\Components\TextInput::make('sgpt')
                    ->numeric(),
                Forms\Components\Textarea::make('hasil_pemeriksaan')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('interpretasi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status_hasil')
                    ->required(),
                Forms\Components\Textarea::make('saran_tindak_lanjut')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('nama_petugas_lab'),
                Forms\Components\Textarea::make('file_hasil')
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
                Tables\Columns\TextColumn::make('puskesmas_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pemeriksaan_anc_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_lab')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pemeriksaan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usia_kehamilan_minggu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_pemeriksaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hemoglobin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('leukosit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eritrosit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('trombosit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hematokrit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mcv')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mch')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mchc')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('golongan_darah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rhesus')
                    ->searchable(),
                Tables\Columns\TextColumn::make('warna_urine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kejernihan_urine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ph_urine')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('berat_jenis_urine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('protein_urine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('glukosa_urine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keton_urine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bilirubin_urine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('urobilinogen_urine')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leukosit_urine')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eritrosit_urine')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hbsag')
                    ->searchable(),
                Tables\Columns\TextColumn::make('anti_hcv')
                    ->searchable(),
                Tables\Columns\TextColumn::make('anti_hiv')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vdrl_sifilis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tpha_sifilis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('toxoplasma_igg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('toxoplasma_igm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rubella_igg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rubella_igm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cmv_igg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cmv_igm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gula_darah_puasa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gula_darah_2jam_pp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gula_darah_sewaktu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hba1c')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ureum')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kreatinin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asam_urat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sgot')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sgpt')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_hasil')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_petugas_lab')
                    ->searchable(),
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
            'index' => Pages\ListHasilLaboratoria::route('/'),
            'create' => Pages\CreateHasilLaboratorium::route('/create'),
            'edit' => Pages\EditHasilLaboratorium::route('/{record}/edit'),
        ];
    }
}
