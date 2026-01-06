<?php

namespace App\Filament\Puskesmas\Resources;

use App\Filament\Puskesmas\Resources\SkriningRisikoResource\Pages;
use App\Models\SkriningRisiko;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SkriningRisikoResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = SkriningRisiko::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Pemeriksaan & Skrining';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Skrining Risiko';

    protected static ?string $modelLabel = 'Skrining Risiko';

    protected static ?string $pluralModelLabel = 'Skrining Risiko';

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

    // Data Scoping
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->puskesmas) {
            return $query->where('puskesmas_id', $user->puskesmas->id);
        } elseif ($user->tenagaKesehatan) {
            return $query->where('puskesmas_id', $user->tenagaKesehatan->puskesmas_id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Select::make('ibu_hamil_id')
                            ->relationship(
                                'ibuHamil',
                                'nama_lengkap',
                                fn(Builder $query) => $query->where('puskesmas_id', function () {
                                    $user = auth()->user();
                                    return $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;
                                })
                            )
                            ->label('Ibu Hamil')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $ibuHamil = \App\Models\IbuHamil::find($state);
                                    $set('puskesmas_id', $ibuHamil->puskesmas_id);
                                    $set('usia_kehamilan_minggu', $ibuHamil->usia_kehamilan_minggu);
                                }
                            }),
                        Forms\Components\Hidden::make('puskesmas_id')
                            ->default(function () {
                                $user = auth()->user();
                                return $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;
                            })
                            ->required(),
                        Forms\Components\Select::make('tenaga_kesehatan_id')
                            ->relationship(
                                'tenagaKesehatan',
                                'nip',
                                fn(Builder $query) => $query->where('puskesmas_id', function () {
                                    $user = auth()->user();
                                    return $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;
                                })
                            )
                            ->label('Tenaga Kesehatan')
                            ->searchable()
                            ->preload()
                            ->default(function () {
                                $user = auth()->user();
                                return $user->tenagaKesehatan?->id;
                            })
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->user->nama),
                        Forms\Components\TextInput::make('no_skrining')
                            ->label('No. Skrining')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn() => 'SKR-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT))
                            ->maxLength(30),
                        Forms\Components\DatePicker::make('tanggal_skrining')
                            ->label('Tanggal Skrining')
                            ->required()
                            ->native(false)
                            ->default(now()),
                        Forms\Components\TextInput::make('usia_kehamilan_minggu')
                            ->label('Usia Kehamilan')
                            ->numeric()
                            ->suffix('minggu'),
                        Forms\Components\Select::make('jenis_skrining')
                            ->label('Jenis Skrining')
                            ->options([
                                'mandiri' => 'Mandiri (Ibu Hamil)',
                                'tenaga_kesehatan' => 'Oleh Tenaga Kesehatan',
                            ])
                            ->required()
                            ->default('tenaga_kesehatan'),
                    ])->columns(2),

                Forms\Components\Section::make('Kelompok Faktor Risiko I (Skor 4)')
                    ->schema([
                        Forms\Components\Checkbox::make('primigravida_terlalu_muda')
                            ->label('Primigravida ≤16 tahun')
                            ->reactive(),
                        Forms\Components\Checkbox::make('primigravida_terlalu_tua')
                            ->label('Primigravida ≥35 tahun')
                            ->reactive(),
                        Forms\Components\Checkbox::make('primigravida_tua_sekunder')
                            ->label('Primigravida tua sekunder')
                            ->reactive(),
                        Forms\Components\Checkbox::make('tinggi_badan_rendah')
                            ->label('Tinggi badan ≤145 cm')
                            ->reactive(),
                        Forms\Components\Checkbox::make('gagal_kehamilan')
                            ->label('Gagal kehamilan')
                            ->reactive(),
                        Forms\Components\Checkbox::make('riwayat_vakum_forceps')
                            ->label('Riwayat vakum/forceps')
                            ->reactive(),
                        Forms\Components\Checkbox::make('riwayat_operasi_sesar')
                            ->label('Riwayat SC')
                            ->reactive(),
                    ])->columns(2),

                Forms\Components\Section::make('Kelompok Faktor Risiko II')
                    ->schema([
                        Forms\Components\Checkbox::make('bayi_berat_rendah')
                            ->label('BBLR <2500g (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('bayi_cacat_bawaan')
                            ->label('Bayi cacat bawaan (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('kurang_gizi_anemia')
                            ->label('Anemia HB<11g (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('penyakit_kronis')
                            ->label('Penyakit kronis (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('kelainan_obstetri')
                            ->label('Kelainan obstetri (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('anak_terkecil_under_2')
                            ->label('Anak <2 tahun (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('hamil_kembar')
                            ->label('Hamil kembar (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('hidramnion')
                            ->label('Hidramnion (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('bayi_mati_kandungan')
                            ->label('Bayi mati (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('kehamilan_lebih_bulan')
                            ->label('Lewat bulan (4)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('letak_sungsang')
                            ->label('Sungsang (8)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('letak_lintang')
                            ->label('Lintang (8)')
                            ->reactive(),
                    ])->columns(3),

                Forms\Components\Section::make('Kelompok Faktor Risiko III (Skor 8)')
                    ->schema([
                        Forms\Components\Checkbox::make('perdarahan_kehamilan')
                            ->label('Perdarahan')
                            ->reactive(),
                        Forms\Components\Checkbox::make('preeklampsia')
                            ->label('Preeklampsia')
                            ->reactive(),
                        Forms\Components\Checkbox::make('eklampsia')
                            ->label('Eklampsia')
                            ->reactive(),
                    ])->columns(3),

                Forms\Components\Section::make('Hasil Skrining')
                    ->schema([
                        Forms\Components\TextInput::make('total_skor')
                            ->label('Total Skor')
                            ->required()
                            ->numeric()
                            ->default(2)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Select::make('kategori_risiko')
                            ->label('Kategori Risiko')
                            ->options([
                                'KRR' => 'KRR - Risiko Rendah',
                                'KRT' => 'KRT - Risiko Tinggi',
                                'KRST' => 'KRST - Risiko Sangat Tinggi',
                            ])
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('rekomendasi_tempat_bersalin')
                            ->label('Rekomendasi Tempat')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'final' => 'Final',
                            ])
                            ->required()
                            ->default('final'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_skrining')
                    ->label('No.')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_skrining')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ibuHamil.nama_lengkap')
                    ->label('Ibu Hamil')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('usia_kehamilan_minggu')
                    ->label('UK')
                    ->suffix(' mg')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_skor')
                    ->label('Skor')
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold')
                    ->color(fn($state) => match (true) {
                        $state <= 6 => 'success',
                        $state <= 12 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\BadgeColumn::make('kategori_risiko')
                    ->label('Kategori')
                    ->colors([
                        'success' => 'KRR',
                        'warning' => 'KRT',
                        'danger' => 'KRST',
                    ])
                    ->formatStateUsing(fn(string $state): string => $state),
                Tables\Columns\BadgeColumn::make('jenis_skrining')
                    ->label('Jenis')
                    ->colors([
                        'info' => 'mandiri',
                        'success' => 'tenaga_kesehatan',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'mandiri' => 'Mandiri',
                        'tenaga_kesehatan' => 'Nakes',
                        default => $state,
                    })
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'final',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_risiko')
                    ->label('Kategori')
                    ->options([
                        'KRR' => 'KRR',
                        'KRT' => 'KRT',
                        'KRST' => 'KRST',
                    ]),
                Tables\Filters\Filter::make('bulan_ini')
                    ->label('Bulan Ini')
                    ->query(fn($query) => $query->whereMonth('tanggal_skrining', now()->month)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_skrining', 'desc');
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
            'index' => Pages\ListSkriningRisikos::route('/'),
            'create' => Pages\CreateSkriningRisiko::route('/create'),
            'edit' => Pages\EditSkriningRisiko::route('/{record}/edit'),
            'view' => Pages\ViewSkriningRisiko::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();
        $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;

        return SkriningRisiko::where('puskesmas_id', $puskesmasId)
            ->where('kategori_risiko', 'KRST')
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
