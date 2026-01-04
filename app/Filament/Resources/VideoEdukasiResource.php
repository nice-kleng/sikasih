<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoEdukasiResource\Pages;
use App\Filament\Resources\VideoEdukasiResource\RelationManagers;
use App\Models\VideoEdukasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class VideoEdukasiResource extends Resource
{
    protected static ?string $model = VideoEdukasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Konten & Edukasi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Video Edukasi';

    protected static ?string $modelLabel = 'Video Edukasi';

    protected static ?string $pluralModelLabel = 'Video Edukasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Video')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Video')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('URL-friendly version dari judul')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'persiapan_kehamilan' => 'Persiapan Kehamilan',
                                'perkembangan_janin' => 'Perkembangan Janin',
                                'senam_hamil' => 'Senam Hamil',
                                'nutrisi' => 'Nutrisi',
                                'persiapan_persalinan' => 'Persiapan Persalinan',
                                'perawatan_bayi' => 'Perawatan Bayi',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required()
                            ->searchable(),
                    ])->columns(2),

                Forms\Components\Section::make('Link YouTube')
                    ->description('Masukkan ID video YouTube atau link lengkap')
                    ->schema([
                        Forms\Components\TextInput::make('youtube_id')
                            ->label('YouTube Video ID')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Contoh: jika link YouTube adalah https://www.youtube.com/watch?v=ABC123xyz, maka ID-nya adalah ABC123xyz')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                // Extract YouTube ID from various URL formats
                                if (Str::contains($state, 'youtube.com') || Str::contains($state, 'youtu.be')) {
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $state, $matches);
                                    if (isset($matches[1])) {
                                        $set('youtube_id', $matches[1]);
                                    }
                                }

                                // Auto-generate thumbnail
                                if (strlen($state) === 11 || (isset($matches[1]) && strlen($matches[1]) === 11)) {
                                    $videoId = $matches[1] ?? $state;
                                    $set('thumbnail', "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg");
                                }
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('thumbnail')
                            ->label('Thumbnail URL')
                            ->url()
                            ->maxLength(255)
                            ->helperText('Otomatis terisi dari YouTube. Bisa diganti dengan URL custom.')
                            ->columnSpanFull(),
                        Forms\Components\ViewField::make('youtube_preview')
                            ->label('Preview Video')
                            ->view('filament.forms.components.youtube-preview')
                            ->visible(fn(Forms\Get $get) => filled($get('youtube_id')))
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\TextInput::make('durasi_detik')
                            ->label('Durasi (detik)')
                            ->numeric()
                            ->helperText('Opsional - untuk statistik'),
                        Forms\Components\TextInput::make('urutan')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->helperText('Semakin kecil angka, semakin di atas urutannya'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->required()
                            ->default('active'),
                        Forms\Components\TextInput::make('views')
                            ->label('Jumlah Views')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->size(80),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('kategori')
                    ->label('Kategori')
                    ->colors([
                        'primary' => 'persiapan_kehamilan',
                        'success' => 'perkembangan_janin',
                        'warning' => 'senam_hamil',
                        'info' => 'nutrisi',
                        'danger' => 'persiapan_persalinan',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'persiapan_kehamilan' => 'Persiapan Kehamilan',
                        'perkembangan_janin' => 'Perkembangan Janin',
                        'senam_hamil' => 'Senam Hamil',
                        'nutrisi' => 'Nutrisi',
                        'persiapan_persalinan' => 'Persiapan Persalinan',
                        'perawatan_bayi' => 'Perawatan Bayi',
                        'lainnya' => 'Lainnya',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('durasi_format')
                    ->label('Durasi')
                    ->getStateUsing(fn(VideoEdukasi $record) => $record->durasi_format)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => number_format($state)),
                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'persiapan_kehamilan' => 'Persiapan Kehamilan',
                        'perkembangan_janin' => 'Perkembangan Janin',
                        'senam_hamil' => 'Senam Hamil',
                        'nutrisi' => 'Nutrisi',
                        'persiapan_persalinan' => 'Persiapan Persalinan',
                        'perawatan_bayi' => 'Perawatan Bayi',
                        'lainnya' => 'Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-play')
                    ->color('info')
                    ->url(fn(VideoEdukasi $record) => $record->youtube_url, shouldOpenInNewTab: true),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn($records) => $records->each->update(['status' => 'active'])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn($records) => $records->each->update(['status' => 'inactive'])),
                ]),
            ])
            ->defaultSort('urutan', 'asc')
            ->reorderable('urutan');
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
            'index' => Pages\ListVideoEdukasis::route('/'),
            'create' => Pages\CreateVideoEdukasi::route('/create'),
            'edit' => Pages\EditVideoEdukasi::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
}
