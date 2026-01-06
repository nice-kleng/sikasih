<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemeriksaanAncResource\Pages;
use App\Models\PemeriksaanAnc;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class PemeriksaanAncResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PemeriksaanAnc::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Data Pemeriksaan';

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
            'delete',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pemeriksaan')
                    ->schema([
                        Forms\Components\Select::make('ibu_hamil_id')
                            ->relationship('ibuHamil', 'nama_lengkap')
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
                        Forms\Components\Select::make('puskesmas_id')
                            ->relationship('puskesmas', 'nama_puskesmas')
                            ->label('Puskesmas')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Select::make('tenaga_kesehatan_id')
                            ->relationship('tenagaKesehatan', 'nip')
                            ->label('Tenaga Kesehatan')
                            ->required()
                            ->searchable()
                            ->preload()
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
                            ->label('Tekanan Darah Sistol')
                            ->required()
                            ->numeric()
                            ->suffix('mmHg')
                            ->minValue(70)
                            ->maxValue(200),
                        Forms\Components\TextInput::make('tekanan_darah_diastol')
                            ->label('Tekanan Darah Diastol')
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
                            ->label('LILA (Lingkar Lengan Atas)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm'),
                    ])->columns(4),

                Forms\Components\Section::make('Pemeriksaan Fisik Kehamilan')
                    ->schema([
                        Forms\Components\TextInput::make('tinggi_fundus')
                            ->label('Tinggi Fundus Uteri')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('djj')
                            ->label('DJJ (Denyut Jantung Janin)')
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
                            ->label('Taksiran Berat Janin')
                            ->numeric()
                            ->suffix('gram'),
                    ])->columns(3),

                Forms\Components\Section::make('Pemeriksaan Laboratorium (Jika Ada)')
                    ->schema([
                        Forms\Components\TextInput::make('hb')
                            ->label('Hemoglobin (HB)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('g/dL')
                            ->helperText('Normal: ≥11 g/dL'),
                        Forms\Components\Select::make('golongan_darah')
                            ->label('Golongan Darah')
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
                        Forms\Components\Select::make('hbsag')
                            ->label('HBsAg')
                            ->options([
                                'Reaktif' => 'Reaktif',
                                'Non-Reaktif' => 'Non-Reaktif',
                                'Belum Diperiksa' => 'Belum Diperiksa',
                            ]),
                        Forms\Components\Select::make('hiv')
                            ->label('HIV')
                            ->options([
                                'Reaktif' => 'Reaktif',
                                'Non-Reaktif' => 'Non-Reaktif',
                                'Belum Diperiksa' => 'Belum Diperiksa',
                            ]),
                        Forms\Components\Select::make('sifilis')
                            ->label('Sifilis')
                            ->options([
                                'Reaktif' => 'Reaktif',
                                'Non-Reaktif' => 'Non-Reaktif',
                                'Belum Diperiksa' => 'Belum Diperiksa',
                            ]),
                    ])->columns(3)->collapsed(),

                Forms\Components\Section::make('Anamnesa & Diagnosis')
                    ->schema([
                        Forms\Components\Textarea::make('keluhan')
                            ->label('Keluhan')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('riwayat_penyakit')
                            ->label('Riwayat Penyakit')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('riwayat_alergi')
                            ->label('Riwayat Alergi')
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
                            ->label('Terapi Obat')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('edukasi')
                            ->label('Edukasi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Tambahan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Kunjungan Berikutnya & Rujukan')
                    ->schema([
                        Forms\Components\DatePicker::make('jadwal_kunjungan_berikutnya')
                            ->label('Jadwal Kunjungan Berikutnya')
                            ->native(false),
                        Forms\Components\Select::make('status_pemeriksaan')
                            ->label('Status Pemeriksaan')
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
                            ->rows(3)
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
                Tables\Columns\TextColumn::make('ibuHamil.no_rm')
                    ->label('No. RM')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('kunjungan_ke')
                    ->label('ANC Ke-')
                    ->color('primary')
                    ->formatStateUsing(fn($state) => "Ke-{$state}"),
                Tables\Columns\TextColumn::make('usia_kehamilan_minggu')
                    ->label('UK')
                    ->suffix(' mgg')
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
                Tables\Columns\TextColumn::make('djj')
                    ->label('DJJ')
                    ->suffix(' bpm')
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('puskesmas.nama_puskesmas')
                    ->label('Puskesmas')
                    ->searchable()
                    ->toggleable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('tenagaKesehatan.user.nama')
                    ->label('Pemeriksa')
                    ->searchable()
                    ->toggleable()
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('status_pemeriksaan')
                    ->label('Status')
                    ->colors([
                        'success' => 'selesai',
                        'warning' => 'rujukan',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('puskesmas_id')
                    ->label('Puskesmas')
                    ->relationship('puskesmas', 'nama_puskesmas')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status_pemeriksaan')
                    ->label('Status')
                    ->options([
                        'selesai' => 'Selesai',
                        'rujukan' => 'Rujukan',
                    ]),
                Tables\Filters\Filter::make('tanggal_pemeriksaan')
                    ->form([
                        Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari'], fn($q, $date) => $q->whereDate('tanggal_pemeriksaan', '>=', $date))
                            ->when($data['sampai'], fn($q, $date) => $q->whereDate('tanggal_pemeriksaan', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Edit action with 24-hour rule check
                Tables\Actions\EditAction::make()
                    ->visible(function (PemeriksaanAnc $record) {
                        // Check custom gate for 24h rule
                        return auth()->user()->can('update_pemeriksaan_within_24h', $record);
                    }),

                // Delete action - only puskesmas and super_admin
                Tables\Actions\DeleteAction::make()
                    ->visible(function (PemeriksaanAnc $record) {
                        return auth()->user()->can('delete_pemeriksaan_anc', $record);
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->hasAnyRole(['super_admin', 'puskesmas'])),
                ]),
            ])
            ->defaultSort('tanggal_pemeriksaan', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pemeriksaan')
                    ->schema([
                        Infolists\Components\TextEntry::make('no_pemeriksaan'),
                        Infolists\Components\TextEntry::make('tanggal_pemeriksaan')->date('d F Y'),
                        Infolists\Components\TextEntry::make('ibuHamil.nama_lengkap')->label('Ibu Hamil'),
                        Infolists\Components\TextEntry::make('kunjungan_ke')->badge()->formatStateUsing(fn($state) => "ANC Ke-{$state}"),
                        Infolists\Components\TextEntry::make('usia_kehamilan_minggu')->suffix(' minggu'),
                        Infolists\Components\TextEntry::make('tenagaKesehatan.user.nama')->label('Pemeriksa'),
                    ])->columns(3),

                Infolists\Components\Section::make('Vital Signs')
                    ->schema([
                        Infolists\Components\TextEntry::make('berat_badan')->suffix(' kg'),
                        Infolists\Components\TextEntry::make('tekanan_darah')->badge(),
                        Infolists\Components\TextEntry::make('djj')->suffix(' bpm'),
                        Infolists\Components\TextEntry::make('tinggi_fundus')->suffix(' cm'),
                    ])->columns(4),

                Infolists\Components\Section::make('Diagnosis & Tindakan')
                    ->schema([
                        Infolists\Components\TextEntry::make('diagnosis')->markdown(),
                        Infolists\Components\TextEntry::make('tindakan')->markdown(),
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
            'index' => Pages\ListPemeriksaanAncs::route('/'),
            'create' => Pages\CreatePemeriksaanAnc::route('/create'),
            'edit' => Pages\EditPemeriksaanAnc::route('/{record}/edit'),
            'view' => Pages\ViewPemeriksaanAnc::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereMonth('tanggal_pemeriksaan', now()->month)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
