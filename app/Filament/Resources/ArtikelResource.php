<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtikelResource\Pages;
use App\Models\Artikel;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArtikelResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Artikel::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Konten & Edukasi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Artikel';

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $pluralModelLabel = 'Artikel';

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
                Forms\Components\Section::make('Informasi Artikel')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Artikel')
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
                        Forms\Components\Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'nutrisi' => 'Nutrisi',
                                'olahraga' => 'Olahraga',
                                'perkembangan_janin' => 'Perkembangan Janin',
                                'tanda_bahaya' => 'Tanda Bahaya',
                                'persiapan_persalinan' => 'Persiapan Persalinan',
                                'tips_kehamilan' => 'Tips Kehamilan',
                                'kesehatan_ibu' => 'Kesehatan Ibu',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('penulis_id')
                            ->relationship('penulis', 'nama')
                            ->label('Penulis')
                            ->searchable()
                            ->preload()
                            ->default(fn() => auth()->id()),
                    ])->columns(2),

                Forms\Components\Section::make('Konten')
                    ->schema([
                        Forms\Components\FileUpload::make('gambar_utama')
                            ->label('Gambar Utama')
                            ->image()
                            ->imageEditor()
                            ->directory('artikel')
                            ->maxSize(2048)
                            ->helperText('Ukuran maksimal 2MB')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Ringkasan singkat artikel (max 500 karakter)')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('konten')
                            ->label('Konten Artikel')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'heading',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Metadata')
                    ->schema([
                        Forms\Components\TagsInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Ketik dan tekan Enter')
                            ->helperText('Tag untuk memudahkan pencarian')
                            ->separator(',')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Pengaturan Publikasi')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->required()
                            ->default('draft')
                            ->reactive(),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->native(false)
                            ->visible(fn(Forms\Get $get) => $get('status') === 'published')
                            ->default(now()),
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
                Tables\Columns\ImageColumn::make('gambar_utama')
                    ->label('Gambar')
                    ->circular()
                    ->defaultImageUrl(url('/images/no-image.png')),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('kategori')
                    ->label('Kategori')
                    ->colors([
                        'primary' => 'nutrisi',
                        'success' => 'olahraga',
                        'info' => 'perkembangan_janin',
                        'danger' => 'tanda_bahaya',
                        'warning' => 'persiapan_persalinan',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'nutrisi' => 'Nutrisi',
                        'olahraga' => 'Olahraga',
                        'perkembangan_janin' => 'Perkembangan Janin',
                        'tanda_bahaya' => 'Tanda Bahaya',
                        'persiapan_persalinan' => 'Persiapan Persalinan',
                        'tips_kehamilan' => 'Tips Kehamilan',
                        'kesehatan_ibu' => 'Kesehatan Ibu',
                        'lainnya' => 'Lainnya',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('penulis.nama')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'danger' => 'archived',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => number_format($state)),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
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
                        'nutrisi' => 'Nutrisi',
                        'olahraga' => 'Olahraga',
                        'perkembangan_janin' => 'Perkembangan Janin',
                        'tanda_bahaya' => 'Tanda Bahaya',
                        'persiapan_persalinan' => 'Persiapan Persalinan',
                        'tips_kehamilan' => 'Tips Kehamilan',
                        'kesehatan_ibu' => 'Kesehatan Ibu',
                        'lainnya' => 'Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                Tables\Filters\Filter::make('published')
                    ->label('Sudah Dipublikasi')
                    ->query(fn($query) => $query->published()),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn(Artikel $record) => $record->publish())
                    ->visible(fn(Artikel $record) => $record->status !== 'published'),
                Tables\Actions\Action::make('unpublish')
                    ->label('Unpublish')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(fn(Artikel $record) => $record->unpublish())
                    ->visible(fn(Artikel $record) => $record->status === 'published'),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->publish();
                        }),
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
            'index' => Pages\ListArtikels::route('/'),
            'create' => Pages\CreateArtikel::route('/create'),
            'edit' => Pages\EditArtikel::route('/{record}/edit'),
            'view' => Pages\ViewArtikel::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'draft')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
