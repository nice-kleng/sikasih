<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IbuHamilResource\Actions\PrintKartuAction;
use App\Filament\Resources\IbuHamilResource\Pages;
use App\Models\IbuHamil;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class IbuHamilResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = IbuHamil::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Ibu Hamil';

    protected static ?string $modelLabel = 'Ibu Hamil';

    protected static ?string $pluralModelLabel = 'Ibu Hamil';

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
                Forms\Components\Section::make('Data Utama')
                    ->schema([
                        Forms\Components\Select::make('puskesmas_id')
                            ->relationship('puskesmas', 'nama_puskesmas')
                            ->label('Puskesmas')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('no_rm')
                            ->label('No. Rekam Medis')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->length(16)
                            ->numeric(),
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $umur = \Carbon\Carbon::parse($state)->age;
                                    $set('umur', $umur);
                                }
                            }),
                        Forms\Components\TextInput::make('umur')
                            ->label('Umur')
                            ->required()
                            ->numeric()
                            ->suffix('tahun')
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(2),

                Forms\Components\Section::make('Alamat')
                    ->schema([
                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('rt')
                            ->label('RT')
                            ->maxLength(5),
                        Forms\Components\TextInput::make('rw')
                            ->label('RW')
                            ->maxLength(5),
                        Forms\Components\TextInput::make('kelurahan')
                            ->label('Kelurahan/Desa')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('kecamatan')
                            ->label('Kecamatan')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('kabupaten')
                            ->label('Kabupaten/Kota')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('provinsi')
                            ->label('Provinsi')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->maxLength(10),
                    ])->columns(4),

                Forms\Components\Section::make('Data Pribadi')
                    ->schema([
                        Forms\Components\Select::make('golongan_darah')
                            ->label('Golongan Darah')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'AB' => 'AB',
                                'O' => 'O',
                                'Tidak Tahu' => 'Tidak Tahu',
                            ]),
                        Forms\Components\Select::make('pendidikan_terakhir')
                            ->label('Pendidikan Terakhir')
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP',
                                'SMA/SMK' => 'SMA/SMK',
                                'D3' => 'D3',
                                'S1' => 'S1',
                                'S2' => 'S2',
                                'S3' => 'S3',
                            ]),
                        Forms\Components\TextInput::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Data Suami')
                    ->schema([
                        Forms\Components\TextInput::make('nama_suami')
                            ->label('Nama Suami')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('umur_suami')
                            ->label('Umur Suami')
                            ->numeric()
                            ->suffix('tahun'),
                        Forms\Components\TextInput::make('pekerjaan_suami')
                            ->label('Pekerjaan Suami')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('pendidikan_suami')
                            ->label('Pendidikan Suami')
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP',
                                'SMA/SMK' => 'SMA/SMK',
                                'D3' => 'D3',
                                'S1' => 'S1',
                                'S2' => 'S2',
                                'S3' => 'S3',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Data Reproduksi & Kehamilan')
                    ->schema([
                        Forms\Components\TextInput::make('gravida')
                            ->label('Gravida (G)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Jumlah kehamilan'),
                        Forms\Components\TextInput::make('para')
                            ->label('Para (P)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Jumlah persalinan'),
                        Forms\Components\TextInput::make('abortus')
                            ->label('Abortus (Ab)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Jumlah keguguran'),
                        Forms\Components\TextInput::make('anak_hidup')
                            ->label('Anak Hidup (A)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Jumlah anak hidup'),
                        Forms\Components\TextInput::make('usia_menikah')
                            ->label('Usia Menikah')
                            ->numeric()
                            ->suffix('tahun'),
                        Forms\Components\TextInput::make('usia_hamil_pertama')
                            ->label('Usia Hamil Pertama')
                            ->numeric()
                            ->suffix('tahun'),
                        Forms\Components\Textarea::make('riwayat_persalinan')
                            ->label('Riwayat Persalinan')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('jarak_kehamilan_terakhir')
                            ->label('Jarak Kehamilan Terakhir')
                            ->maxLength(255)
                            ->placeholder('Contoh: 2 tahun 6 bulan'),
                        Forms\Components\Textarea::make('riwayat_komplikasi')
                            ->label('Riwayat Komplikasi')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('BPJS')
                    ->schema([
                        Forms\Components\Select::make('memiliki_bpjs')
                            ->label('Memiliki BPJS')
                            ->options([
                                'Ya' => 'Ya',
                                'Tidak' => 'Tidak',
                            ])
                            ->required()
                            ->default('Tidak')
                            ->reactive(),
                        Forms\Components\TextInput::make('no_bpjs')
                            ->label('No. BPJS')
                            ->maxLength(20)
                            ->visible(fn(Forms\Get $get) => $get('memiliki_bpjs') === 'Ya'),
                    ])->columns(2),

                Forms\Components\Section::make('Kehamilan Saat Ini')
                    ->schema([
                        Forms\Components\DatePicker::make('hpht')
                            ->label('HPHT (Hari Pertama Haid Terakhir)')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $hpl = \Carbon\Carbon::parse($state)->addDays(280);
                                    $set('hpl', $hpl->format('Y-m-d'));

                                    $usiaKehamilan = floor(\Carbon\Carbon::parse($state)->diffInDays(now()) / 7);
                                    $set('usia_kehamilan_minggu', $usiaKehamilan);
                                }
                            }),
                        Forms\Components\DatePicker::make('hpl')
                            ->label('HPL (Hari Perkiraan Lahir)')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('usia_kehamilan_minggu')
                            ->label('Usia Kehamilan')
                            ->numeric()
                            ->suffix('minggu')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('berat_badan_awal')
                            ->label('Berat Badan Awal')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('kg'),
                        Forms\Components\TextInput::make('tinggi_badan')
                            ->label('Tinggi Badan')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm'),
                        Forms\Components\Select::make('status_kehamilan')
                            ->label('Status Kehamilan')
                            ->options([
                                'hamil' => 'Hamil',
                                'melahirkan' => 'Melahirkan',
                                'nifas' => 'Nifas',
                                'selesai' => 'Selesai',
                            ])
                            ->required()
                            ->default('hamil'),
                        Forms\Components\Select::make('status')
                            ->label('Status Aktif')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Non-aktif',
                            ])
                            ->required()
                            ->default('aktif'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_rm')
                    ->label('No. RM')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('umur')
                    ->label('Umur')
                    ->sortable()
                    ->suffix(' th')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('puskesmas.nama_puskesmas')
                    ->label('Puskesmas')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('usia_kehamilan_minggu')
                    ->label('Usia Kehamilan')
                    ->suffix(' minggu')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\BadgeColumn::make('trimester')
                    ->label('Trimester')
                    ->getStateUsing(fn(IbuHamil $record) => $record->trimester)
                    ->colors([
                        'primary' => 1,
                        'success' => 2,
                        'warning' => 3,
                    ])
                    ->formatStateUsing(fn($state) => "Trimester {$state}"),
                Tables\Columns\TextColumn::make('statusObstetri')
                    ->label('Status Obstetri')
                    ->badge()
                    ->color('info'),
                Tables\Columns\BadgeColumn::make('status_kehamilan')
                    ->label('Status')
                    ->colors([
                        'success' => 'hamil',
                        'warning' => 'melahirkan',
                        'info' => 'nifas',
                        'gray' => 'selesai',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('puskesmas_id')
                    ->label('Puskesmas')
                    ->relationship('puskesmas', 'nama_puskesmas')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status_kehamilan')
                    ->label('Status Kehamilan')
                    ->options([
                        'hamil' => 'Hamil',
                        'melahirkan' => 'Melahirkan',
                        'nifas' => 'Nifas',
                        'selesai' => 'Selesai',
                    ]),
                Tables\Filters\Filter::make('trimester')
                    ->form([
                        Forms\Components\Select::make('trimester')
                            ->label('Trimester')
                            ->options([
                                1 => 'Trimester 1',
                                2 => 'Trimester 2',
                                3 => 'Trimester 3',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['trimester'])) {
                            return $query->trimester($data['trimester']);
                        }
                    }),
            ])
            ->actions([
                PrintKartuAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Data Utama')
                    ->schema([
                        Infolists\Components\TextEntry::make('no_rm')->label('No. RM'),
                        Infolists\Components\TextEntry::make('nik')->label('NIK'),
                        Infolists\Components\TextEntry::make('nama_lengkap')->label('Nama Lengkap'),
                        Infolists\Components\TextEntry::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->date('d F Y'),
                        Infolists\Components\TextEntry::make('umur')
                            ->label('Umur')
                            ->suffix(' tahun'),
                        Infolists\Components\TextEntry::make('puskesmas.nama_puskesmas')
                            ->label('Puskesmas'),
                    ])->columns(2),

                Infolists\Components\Section::make('Kehamilan Saat Ini')
                    ->schema([
                        Infolists\Components\TextEntry::make('statusObstetri')
                            ->label('Status Obstetri')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('hpht')
                            ->label('HPHT')
                            ->date('d F Y'),
                        Infolists\Components\TextEntry::make('hpl')
                            ->label('HPL')
                            ->date('d F Y'),
                        Infolists\Components\TextEntry::make('usia_kehamilan_minggu')
                            ->label('Usia Kehamilan')
                            ->suffix(' minggu'),
                        Infolists\Components\TextEntry::make('trimester')
                            ->label('Trimester')
                            ->badge()
                            ->formatStateUsing(fn($state) => "Trimester {$state}"),
                        Infolists\Components\TextEntry::make('status_kehamilan')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                    ])->columns(3),
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
            'index' => Pages\ListIbuHamils::route('/'),
            'create' => Pages\CreateIbuHamil::route('/create'),
            'edit' => Pages\EditIbuHamil::route('/{record}/edit'),
            'view' => Pages\ViewIbuHamil::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_kehamilan', 'hamil')->count();
    }
}
