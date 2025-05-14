<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Photo;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Slider';

    protected static ?string $navigationGroup = 'Konten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Slider')
                    ->schema([
                        Forms\Components\Select::make('photo_id')
                            ->label('Pilih Foto')
                            ->options(function (?Slider $record = null) {
                                // Ambil semua ID foto yang sudah digunakan di slider
                                $usedPhotoIds = $record
                                    ? Slider::where('id', '!=', $record->id)->pluck('photo_id')->toArray()
                                    : Slider::pluck('photo_id')->toArray();

                                // Query foto yang belum digunakan atau foto yang sedang diedit
                                $query = Photo::query();
                                if (!empty($usedPhotoIds)) {
                                    $query->whereNotIn('id', $usedPhotoIds);

                                    // Jika sedang mengedit data, tambahkan foto yang digunakan pada slider ini
                                    if ($record && $record->photo_id) {
                                        $query->orWhere('id', $record->photo_id);
                                    }
                                }

                                // Return hasil query
                                return $query->get()->pluck('file_path', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->helperText('Hanya menampilkan foto yang belum digunakan pada slider lain')
                            ->afterStateUpdated(fn($state, callable $set) => $set('preview', $state))
                            ->afterStateHydrated(fn($state, callable $set) => $set('preview', $state))
                            ->columnSpanFull(),

                        Forms\Components\ViewField::make('preview')
                            ->label('Preview Foto')
                            ->view('filament.forms.components.photo-preview')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('slide_number')
                            ->label('Posisi Slider')
                            ->options(array_combine(range(1, 10), range(1, 10)))
                            ->required()
                            ->default(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slide_number')
                    ->label('Posisi Slider')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('photo.file_path')
                    ->label('Foto')
                    ->disk('public')
                    ->square(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
