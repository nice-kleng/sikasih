<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenagaKesehatanResource\Pages;
use App\Models\TenagaKesehatan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class TenagaKesehatanResource extends Resource
{
    protected static ?string $model = TenagaKesehatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Tenaga Kesehatan';

    protected static ?string $modelLabel = 'Tenaga Kesehatan';

    protected static ?string $pluralModelLabel = 'Tenaga Kesehatan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi User & Akun')
                    ->schema([
                        Forms\Components\TextInput::make('user.nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user.email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user.password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user.no_telepon')
                            ->label('No. Telepon')
                            ->tel()
                            ->maxLength(15),
                    ])->columns(2),

                Forms\Components\Section::make('Data Kepegawaian')
                    ->schema([
                        Forms\Components\Select::make('puskesmas_id')
                            ->relationship('puskesmas', 'nama_puskesmas')
                            ->label('Puskesmas')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->unique(ignoreRecord: true)
                            ->maxLength(30),
                        Forms\Components\TextInput::make('str')
                            ->label('STR (Surat Tanda Registrasi)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(30),
                        Forms\Components\Select::make('jenis_tenaga')
                            ->label('Jenis Tenaga')
                            ->options([
                                'bidan' => 'Bidan',
                                'dokter' => 'Dokter',
                                'dokter_spesialis' => 'Dokter Spesialis',
                                'perawat' => 'Perawat',
                            ])
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('spesialisasi')
                            ->label('Spesialisasi')
                            ->maxLength(255)
                            ->visible(fn(Forms\Get $get) => $get('jenis_tenaga') === 'dokter_spesialis')
                            ->placeholder('Contoh: Sp.OG, Sp.A, dll'),
                        Forms\Components\Select::make('pendidikan_terakhir')
                            ->label('Pendidikan Terakhir')
                            ->options([
                                'D3 Kebidanan' => 'D3 Kebidanan',
                                'D4 Kebidanan' => 'D4 Kebidanan',
                                'S1 Keperawatan' => 'S1 Keperawatan',
                                'S1 Kedokteran' => 'S1 Kedokteran',
                                'Profesi Bidan' => 'Profesi Bidan',
                                'Profesi Ners' => 'Profesi Ners',
                                'Profesi Dokter' => 'Profesi Dokter',
                                'Sp.OG' => 'Spesialis Kebidanan',
                                'Sp.A' => 'Spesialis Anak',
                            ])
                            ->required()
                            ->maxLength(50),
                    ])->columns(2),

                Forms\Components\Section::make('Data Pribadi')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()->subYears(18)),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Kepegawaian')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_mulai_kerja')
                            ->label('Tanggal Mulai Kerja')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Forms\Components\Select::make('status_kepegawaian')
                            ->label('Status Kepegawaian')
                            ->options([
                                'PNS' => 'PNS',
                                'PPPK' => 'PPPK',
                                'Kontrak' => 'Kontrak',
                                'Honorer' => 'Honorer',
                            ]),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktif',
                                'cuti' => 'Cuti',
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
                Tables\Columns\TextColumn::make('user.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('str')
                    ->label('STR')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\BadgeColumn::make('jenis_tenaga')
                    ->label('Profesi')
                    ->colors([
                        'primary' => 'bidan',
                        'success' => 'dokter',
                        'warning' => 'dokter_spesialis',
                        'info' => 'perawat',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'bidan' => 'Bidan',
                        'dokter' => 'Dokter',
                        'dokter_spesialis' => 'Dokter Spesialis',
                        'perawat' => 'Perawat',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('spesialisasi')
                    ->label('Spesialisasi')
                    ->searchable()
                    ->toggleable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('puskesmas.nama_puskesmas')
                    ->label('Puskesmas')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('pendidikan_terakhir')
                    ->label('Pendidikan')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status_kepegawaian')
                    ->label('Kepegawaian')
                    ->colors([
                        'success' => 'PNS',
                        'primary' => 'PPPK',
                        'warning' => 'Kontrak',
                        'gray' => 'Honorer',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'aktif',
                        'warning' => 'cuti',
                        'danger' => 'nonaktif',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('tanggal_mulai_kerja')
                    ->label('Mulai Kerja')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('jenis_tenaga')
                    ->label('Profesi')
                    ->options([
                        'bidan' => 'Bidan',
                        'dokter' => 'Dokter',
                        'dokter_spesialis' => 'Dokter Spesialis',
                        'perawat' => 'Perawat',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'cuti' => 'Cuti',
                        'nonaktif' => 'Non-aktif',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListTenagaKesehatans::route('/'),
            'create' => Pages\CreateTenagaKesehatan::route('/create'),
            'edit' => Pages\EditTenagaKesehatan::route('/{record}/edit'),
            'view' => Pages\ViewTenagaKesehatan::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'aktif')->count();
    }
}
