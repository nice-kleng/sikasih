<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuskesmasResource\Pages;
use App\Models\Puskesmas;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class PuskesmasResource extends Resource
{
    protected static ?string $model = Puskesmas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Puskesmas';

    protected static ?string $modelLabel = 'Puskesmas';

    protected static ?string $pluralModelLabel = 'Puskesmas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi User & Akun')
                    ->schema([
                        Forms\Components\TextInput::make('user.nama')
                            ->label('Nama Admin Puskesmas')
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
                            ->label('No. Telepon User')
                            ->tel()
                            ->maxLength(15),
                    ])->columns(2),

                Forms\Components\Section::make('Data Puskesmas')
                    ->schema([
                        Forms\Components\TextInput::make('kode_puskesmas')
                            ->label('Kode Puskesmas')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('PKM-XXX-001'),
                        Forms\Components\TextInput::make('nama_puskesmas')
                            ->label('Nama Puskesmas')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
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
                    ])->columns(2),

                Forms\Components\Section::make('Kontak & Kepegawaian')
                    ->schema([
                        Forms\Components\TextInput::make('no_telepon')
                            ->label('No. Telepon Puskesmas')
                            ->tel()
                            ->maxLength(15),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Puskesmas')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kepala_puskesmas')
                            ->label('Kepala Puskesmas')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Fasilitas & Status')
                    ->schema([
                        Forms\Components\TagsInput::make('fasilitas')
                            ->label('Fasilitas')
                            ->placeholder('Ketik dan tekan Enter')
                            ->helperText('Contoh: Ruang KIA, Laboratorium, Apotik, dll')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('tipe')
                            ->label('Tipe Puskesmas')
                            ->options([
                                'poned' => 'PONED',
                                'non_poned' => 'Non PONED',
                            ])
                            ->required()
                            ->default('non_poned'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Non-aktif',
                            ])
                            ->required()
                            ->default('aktif'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_puskesmas')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_puskesmas')
                    ->label('Nama Puskesmas')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('kabupaten')
                    ->label('Kabupaten/Kota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provinsi')
                    ->label('Provinsi')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\BadgeColumn::make('tipe')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'poned',
                        'gray' => 'non_poned',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'poned' => 'PONED',
                        'non_poned' => 'Non PONED',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('tenaga_kesehatan_count')
                    ->label('Tenaga Kesehatan')
                    ->counts('tenagaKesehatan')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('ibu_hamil_count')
                    ->label('Ibu Hamil Aktif')
                    ->counts(['ibuHamil' => fn($query) => $query->where('status_kehamilan', 'hamil')])
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'nonaktif',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->label('Tipe Puskesmas')
                    ->options([
                        'poned' => 'PONED',
                        'non_poned' => 'Non PONED',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-aktif',
                    ]),
                Tables\Filters\SelectFilter::make('provinsi')
                    ->label('Provinsi')
                    ->options(fn() => Puskesmas::distinct()->pluck('provinsi', 'provinsi')->toArray()),
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
            'index' => Pages\ListPuskesmas::route('/'),
            'create' => Pages\CreatePuskesmas::route('/create'),
            'edit' => Pages\EditPuskesmas::route('/{record}/edit'),
            'view' => Pages\ViewPuskesmas::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
