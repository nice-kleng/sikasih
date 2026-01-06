<?php

namespace App\Filament\Puskesmas\Resources;

use App\Filament\Puskesmas\Resources\PemeriksaanAncResource\Pages;
use App\Models\PemeriksaanAnc;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PemeriksaanAncResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PemeriksaanAnc::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Pemeriksaan & Skrining';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Pemeriksaan ANC';

    protected static ?string $modelLabel = 'Pemeriksaan ANC';

    protected static ?string $pluralModelLabel = 'Pemeriksaan ANC';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete', // tenaga_kesehatan won't have this
        ];
    }

    // Data Scoping
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Super admin bypass
        if (auth()->user()->hasRole('super_admin')) {
            return $query;
        }

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
                Forms\Components\Section::make('Informasi Pemeriksaan')
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

                                    // Auto set kunjungan_ke
                                    $lastAnc = \App\Models\PemeriksaanAnc::where('ibu_hamil_id', $state)
                                        ->max('kunjungan_ke');
                                    $set('kunjungan_ke', ($lastAnc ?? 0) + 1);
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
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(function () {
                                $user = auth()->user();
                                return $user->tenagaKesehatan?->id;
                            })
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->user->nama . ' - ' . ucfirst($record->jenis_tenaga)),
                        Forms\Components\TextInput::make('no_pemeriksaan')
                            ->label('No. Pemeriksaan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn() => 'ANC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT))
                            ->maxLength(30),
                        Forms\Components\DatePicker::make('tanggal_pemeriksaan')
                            ->label('Tanggal Pemeriksaan')
                            ->required()
                            ->native(false)
                            ->default(now()),
                        Forms\Components\TimePicker::make('waktu_pemeriksaan')
                            ->label('Waktu Pemeriksaan')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('kunjungan_ke')
                            ->label('Kunjungan ANC Ke-')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                        Forms\Components\TextInput::make('usia_kehamilan_minggu')
                            ->label('Usia Kehamilan')
                            ->required()
                            ->numeric()
                            ->suffix('minggu'),
                    ])->columns(2),

                Forms\Components\Section::make('Vital Signs')
                    ->schema([
                        Forms\Components\TextInput::make('berat_badan')
                            ->label('Berat Badan')
                            ->required()
                            ->numeric()
                            ->step(0.1)
                            ->suffix('kg'),
                        Forms\Components\TextInput::make('tinggi_badan')
                            ->label('Tinggi Badan')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('tekanan_darah_sistol')
                            ->label('TD Sistol')
                            ->required()
                            ->numeric()
                            ->suffix('mmHg')
                            ->minValue(70)
                            ->maxValue(200),
                        Forms\Components\TextInput::make('tekanan_darah_diastol')
                            ->label('TD Diastol')
                            ->required()
                            ->numeric()
                            ->suffix('mmHg')
                            ->minValue(40)
                            ->maxValue(130),
                        Forms\Components\TextInput::make('suhu_tubuh')
                            ->label('Suhu Tubuh')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('°C'),
                        Forms\Components\TextInput::make('nadi')
                            ->label('Nadi')
                            ->numeric()
                            ->suffix('bpm'),
                        Forms\Components\TextInput::make('respirasi')
                            ->label('Respirasi')
                            ->numeric()
                            ->suffix('x/menit'),
                        Forms\Components\TextInput::make('lila')
                            ->label('LILA')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm'),
                    ])->columns(4),

                Forms\Components\Section::make('Pemeriksaan Fisik Kehamilan')
                    ->schema([
                        Forms\Components\TextInput::make('tinggi_fundus')
                            ->label('TFU')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('djj')
                            ->label('DJJ')
                            ->numeric()
                            ->suffix('bpm')
                            ->helperText('Normal: 120-160 bpm'),
                        Forms\Components\Select::make('letak_janin')
                            ->label('Letak Janin')
                            ->options([
                                'kepala' => 'Kepala',
                                'sungsang' => 'Sungsang',
                                'lintang' => 'Lintang',
                            ]),
                        Forms\Components\TextInput::make('presentasi')
                            ->label('Presentasi')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('taksiran_berat_janin')
                            ->label('TBJ')
                            ->numeric()
                            ->suffix('gram'),
                    ])->columns(3),

                Forms\Components\Section::make('Pemeriksaan Lab (Jika Ada)')
                    ->schema([
                        Forms\Components\TextInput::make('hb')
                            ->label('HB')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('g/dL'),
                        Forms\Components\Select::make('golongan_darah')
                            ->label('Gol. Darah')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'AB' => 'AB',
                                'O' => 'O',
                            ]),
                        Forms\Components\Select::make('protein_urin')
                            ->label('Protein Urin')
                            ->options([
                                'Negatif' => 'Negatif',
                                '+1' => '+1',
                                '+2' => '+2',
                                '+3' => '+3',
                                '+4' => '+4',
                            ]),
                        Forms\Components\Select::make('glukosa_urin')
                            ->label('Glukosa Urin')
                            ->options([
                                'Negatif' => 'Negatif',
                                '+1' => '+1',
                                '+2' => '+2',
                                '+3' => '+3',
                                '+4' => '+4',
                            ]),
                    ])->columns(4)->collapsed(),

                Forms\Components\Section::make('Anamnesa')
                    ->schema([
                        Forms\Components\Textarea::make('keluhan')
                            ->label('Keluhan')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('diagnosis')
                            ->label('Diagnosis')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Tindakan & Terapi')
                    ->schema([
                        Forms\Components\Textarea::make('tindakan')
                            ->label('Tindakan')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('terapi_obat')
                            ->label('Terapi')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('edukasi')
                            ->label('Edukasi')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Kunjungan Berikutnya')
                    ->schema([
                        Forms\Components\DatePicker::make('jadwal_kunjungan_berikutnya')
                            ->label('Jadwal Kunjungan Berikutnya')
                            ->native(false),
                        Forms\Components\Select::make('status_pemeriksaan')
                            ->label('Status')
                            ->options([
                                'selesai' => 'Selesai',
                                'rujukan' => 'Rujukan',
                            ])
                            ->required()
                            ->default('selesai')
                            ->reactive(),
                        Forms\Components\TextInput::make('rujukan_ke')
                            ->label('Rujukan Ke')
                            ->maxLength(255)
                            ->visible(fn(Forms\Get $get) => $get('status_pemeriksaan') === 'rujukan'),
                        Forms\Components\Textarea::make('alasan_rujukan')
                            ->label('Alasan Rujukan')
                            ->rows(2)
                            ->visible(fn(Forms\Get $get) => $get('status_pemeriksaan') === 'rujukan')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_pemeriksaan')
                    ->label('No. Pemeriksaan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pemeriksaan')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ibuHamil.nama_lengkap')
                    ->label('Ibu Hamil')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('kunjungan_ke')
                    ->label('ANC')
                    ->color('primary')
                    ->formatStateUsing(fn($state) => "Ke-{$state}"),
                Tables\Columns\TextColumn::make('usia_kehamilan_minggu')
                    ->label('UK')
                    ->suffix(' mg')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('tekanan_darah')
                    ->label('TD')
                    ->getStateUsing(fn(PemeriksaanAnc $record) => $record->tekanan_darah)
                    ->badge()
                    ->color(fn(PemeriksaanAnc $record) => match ($record->statusTekananDarah) {
                        'tinggi' => 'danger',
                        'rendah' => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('berat_badan')
                    ->label('BB')
                    ->suffix(' kg')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('tenagaKesehatan.user.nama')
                    ->label('Pemeriksa')
                    ->searchable()
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status_pemeriksaan')
                    ->label('Status')
                    ->colors([
                        'success' => 'selesai',
                        'warning' => 'rujukan',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pemeriksaan')
                    ->label('Status')
                    ->options([
                        'selesai' => 'Selesai',
                        'rujukan' => 'Rujukan',
                    ]),
                Tables\Filters\Filter::make('bulan_ini')
                    ->label('Bulan Ini')
                    ->query(fn($query) => $query->whereMonth('tanggal_pemeriksaan', now()->month)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Edit dengan 24h rule check
                Tables\Actions\EditAction::make()
                    ->visible(function (PemeriksaanAnc $record) {
                        // Use custom gate
                        return auth()->user()->can('update_pemeriksaan_within_24h', $record);
                    })
                    ->tooltip(function (PemeriksaanAnc $record) {
                        $user = auth()->user();

                        // Show tooltip for tenaga kesehatan
                        if (
                            $user->tenagaKesehatan &&
                            $record->tenaga_kesehatan_id === $user->tenagaKesehatan->id
                        ) {

                            $hoursAgo = $record->created_at->diffInHours(now());

                            if ($hoursAgo >= 24) {
                                return 'Hanya dapat diedit dalam 24 jam pertama';
                            }

                            $hoursLeft = 24 - $hoursAgo;
                            return "Dapat diedit {$hoursLeft} jam lagi";
                        }

                        return null;
                    }),

                // Delete - only puskesmas
                Tables\Actions\DeleteAction::make()
                    ->visible(
                        fn(PemeriksaanAnc $record) =>
                        auth()->user()->can('delete_pemeriksaan_anc', $record)
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_pemeriksaan', 'desc');
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
            'index' => Pages\ListPemeriksaanAncs::route('/'),
            'create' => Pages\CreatePemeriksaanAnc::route('/create'),
            'edit' => Pages\EditPemeriksaanAnc::route('/{record}/edit'),
            'view' => Pages\ViewPemeriksaanAnc::route('/{record}'),
        ];
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     $user = auth()->user();
    //     $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;

    //     return PemeriksaanAnc::where('puskesmas_id', $puskesmasId)
    //         ->whereMonth('tanggal_pemeriksaan', now()->month)
    //         ->count();
    // }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return PemeriksaanAnc::whereMonth('tanggal_pemeriksaan', now()->month)->count();
        }

        $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;

        return PemeriksaanAnc::where('puskesmas_id', $puskesmasId)
            ->whereMonth('tanggal_pemeriksaan', now()->month)
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
