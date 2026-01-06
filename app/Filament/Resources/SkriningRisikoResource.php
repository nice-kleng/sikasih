<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkriningRisikoResource\Pages;
use App\Models\SkriningRisiko;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class SkriningRisikoResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = SkriningRisiko::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Data Pemeriksaan';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Select::make('ibu_hamil_id')
                            ->relationship('ibuHamil', 'nama_lengkap')
                            ->label('Ibu Hamil')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('puskesmas_id')
                            ->relationship('puskesmas', 'nama_puskesmas')
                            ->label('Puskesmas')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('tenaga_kesehatan_id')
                            ->relationship('tenagaKesehatan', 'nip')
                            ->label('Tenaga Kesehatan')
                            ->searchable()
                            ->preload()
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

                Forms\Components\Section::make('Kelompok Faktor Risiko I (Skor 4 per item)')
                    ->description('Centang faktor risiko yang sesuai')
                    ->schema([
                        Forms\Components\Checkbox::make('primigravida_terlalu_muda')
                            ->label('Primigravida terlalu muda (≤ 16 tahun)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('primigravida_terlalu_tua')
                            ->label('Primigravida terlalu tua (≥ 35 tahun)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('primigravida_tua_sekunder')
                            ->label('Primigravida tua sekunder')
                            ->reactive(),
                        Forms\Components\Checkbox::make('tinggi_badan_rendah')
                            ->label('Tinggi badan ≤ 145 cm')
                            ->reactive(),
                        Forms\Components\Checkbox::make('gagal_kehamilan')
                            ->label('Pernah gagal kehamilan')
                            ->reactive(),
                        Forms\Components\Checkbox::make('riwayat_vakum_forceps')
                            ->label('Pernah melahirkan dengan vakum/forceps')
                            ->reactive(),
                        Forms\Components\Checkbox::make('riwayat_operasi_sesar')
                            ->label('Pernah melahirkan dengan operasi sesar')
                            ->reactive(),
                    ])->columns(2),

                Forms\Components\Section::make('Kelompok Faktor Risiko II (Skor 4 & 8)')
                    ->schema([
                        Forms\Components\Checkbox::make('bayi_berat_rendah')
                            ->label('Bayi berat lahir rendah (< 2500 gram) - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('bayi_cacat_bawaan')
                            ->label('Bayi cacat bawaan - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('kurang_gizi_anemia')
                            ->label('Kurang gizi/anemia (HB < 11 g) - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('penyakit_kronis')
                            ->label('Riwayat penyakit kronis - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('kelainan_obstetri')
                            ->label('Kelainan obstetri - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('anak_terkecil_under_2')
                            ->label('Anak terkecil < 2 tahun - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('hamil_kembar')
                            ->label('Hamil kembar (gemelli) - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('hidramnion')
                            ->label('Hamil kembar air (hidramnion) - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('bayi_mati_kandungan')
                            ->label('Bayi mati dalam kandungan - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('kehamilan_lebih_bulan')
                            ->label('Kehamilan lebih bulan - Skor 4')
                            ->reactive(),
                        Forms\Components\Checkbox::make('letak_sungsang')
                            ->label('Letak sungsang - Skor 8')
                            ->reactive(),
                        Forms\Components\Checkbox::make('letak_lintang')
                            ->label('Letak lintang - Skor 8')
                            ->reactive(),
                    ])->columns(2),

                Forms\Components\Section::make('Kelompok Faktor Risiko III (Skor 8 per item)')
                    ->schema([
                        Forms\Components\Checkbox::make('perdarahan_kehamilan')
                            ->label('Perdarahan dalam kehamilan ini')
                            ->reactive(),
                        Forms\Components\Checkbox::make('preeklampsia')
                            ->label('Preeklampsia (tekanan darah tinggi)')
                            ->reactive(),
                        Forms\Components\Checkbox::make('eklampsia')
                            ->label('Preeklampsia berat/eklampsia')
                            ->reactive(),
                    ])->columns(3),

                Forms\Components\Section::make('Hasil Skrining')
                    ->description('Hasil akan dihitung otomatis berdasarkan faktor risiko yang dipilih')
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
                                'KRR' => 'KRR - Kehamilan Risiko Rendah',
                                'KRT' => 'KRT - Kehamilan Risiko Tinggi',
                                'KRST' => 'KRST - Kehamilan Risiko Sangat Tinggi',
                            ])
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('rekomendasi_tempat_bersalin')
                            ->label('Rekomendasi Tempat Bersalin')
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('rekomendasi_tindakan')
                            ->label('Rekomendasi Tindakan')
                            ->rows(4)
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Tambahan')
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
                    ->label('No. Skrining')
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
                Tables\Columns\TextColumn::make('ibuHamil.no_rm')
                    ->label('No. RM')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('puskesmas.nama_puskesmas')
                    ->label('Puskesmas')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('usia_kehamilan_minggu')
                    ->label('Usia Kehamilan')
                    ->suffix(' minggu')
                    ->alignCenter()
                    ->sortable(),
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
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'KRR' => 'Risiko Rendah',
                        'KRT' => 'Risiko Tinggi',
                        'KRST' => 'Risiko Sangat Tinggi',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('rekomendasi_tempat_bersalin')
                    ->label('Rekomendasi')
                    ->wrap()
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('jenis_skrining')
                    ->label('Jenis')
                    ->colors([
                        'info' => 'mandiri',
                        'success' => 'tenaga_kesehatan',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'mandiri' => 'Mandiri',
                        'tenaga_kesehatan' => 'Tenaga Kesehatan',
                        default => $state,
                    })
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'final',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('puskesmas_id')
                    ->label('Puskesmas')
                    ->relationship('puskesmas', 'nama_puskesmas')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('kategori_risiko')
                    ->label('Kategori Risiko')
                    ->options([
                        'KRR' => 'Risiko Rendah (KRR)',
                        'KRT' => 'Risiko Tinggi (KRT)',
                        'KRST' => 'Risiko Sangat Tinggi (KRST)',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_skrining')
                    ->label('Jenis Skrining')
                    ->options([
                        'mandiri' => 'Mandiri',
                        'tenaga_kesehatan' => 'Tenaga Kesehatan',
                    ]),
                Tables\Filters\Filter::make('tanggal_skrining')
                    ->form([
                        Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari'], fn($q, $date) => $q->whereDate('tanggal_skrining', '>=', $date))
                            ->when($data['sampai'], fn($q, $date) => $q->whereDate('tanggal_skrining', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_skrining', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Skrining')
                    ->schema([
                        Infolists\Components\TextEntry::make('no_skrining')->label('No. Skrining'),
                        Infolists\Components\TextEntry::make('tanggal_skrining')
                            ->label('Tanggal')
                            ->date('d F Y'),
                        Infolists\Components\TextEntry::make('ibuHamil.nama_lengkap')
                            ->label('Ibu Hamil'),
                        Infolists\Components\TextEntry::make('ibuHamil.no_rm')
                            ->label('No. RM'),
                        Infolists\Components\TextEntry::make('puskesmas.nama_puskesmas')
                            ->label('Puskesmas'),
                        Infolists\Components\TextEntry::make('tenagaKesehatan.user.nama')
                            ->label('Tenaga Kesehatan')
                            ->default('-'),
                        Infolists\Components\TextEntry::make('usia_kehamilan_minggu')
                            ->label('Usia Kehamilan')
                            ->suffix(' minggu'),
                        Infolists\Components\TextEntry::make('jenis_skrining')
                            ->label('Jenis Skrining')
                            ->badge()
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'mandiri' => 'Mandiri',
                                'tenaga_kesehatan' => 'Oleh Tenaga Kesehatan',
                                default => $state,
                            }),
                    ])->columns(2),

                Infolists\Components\Section::make('Faktor Risiko yang Teridentifikasi')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('daftarFaktorRisiko')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('faktor')
                                    ->label('Faktor Risiko'),
                            ])
                            ->getStateUsing(function (SkriningRisiko $record) {
                                return collect($record->daftarFaktorRisiko)->map(fn($faktor) => [
                                    'faktor' => $faktor
                                ]);
                            }),
                    ]),

                Infolists\Components\Section::make('Hasil Skrining')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_skor')
                            ->label('Total Skor')
                            ->badge()
                            ->size('lg')
                            ->color(fn($state) => match (true) {
                                $state <= 6 => 'success',
                                $state <= 12 => 'warning',
                                default => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('kategoriRisikoLengkap')
                            ->label('Kategori Risiko')
                            ->badge()
                            ->size('lg')
                            ->color(fn(SkriningRisiko $record) => match ($record->kategori_risiko) {
                                'KRR' => 'success',
                                'KRT' => 'warning',
                                'KRST' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('rekomendasi_tempat_bersalin')
                            ->label('Rekomendasi Tempat Bersalin')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('rekomendasi_tindakan')
                            ->label('Rekomendasi Tindakan')
                            ->markdown()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('catatan')
                            ->label('Catatan')
                            ->markdown()
                            ->columnSpanFull()
                            ->visible(fn($state) => filled($state)),
                    ])->columns(2),
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
            'index' => Pages\ListSkriningRisikos::route('/'),
            'create' => Pages\CreateSkriningRisiko::route('/create'),
            'edit' => Pages\EditSkriningRisiko::route('/{record}/edit'),
            'view' => Pages\ViewSkriningRisiko::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('kategori_risiko', 'KRST')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
