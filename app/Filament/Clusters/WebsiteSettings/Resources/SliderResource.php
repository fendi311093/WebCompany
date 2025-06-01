<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources;

use App\Filament\Clusters\WebsiteSettings\Resources\SliderResource\Pages;
use App\Filament\Clusters\WebsiteSettings;
use App\Models\Photo;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Slider';
    protected static ?string $modelLabel = 'Slider';
    protected static ?string $pluralLabel = 'List Sliders';
    protected static ?string $cluster = WebsiteSettings::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->schema([
                        Select::make('photo_id')
                            ->label('Select Photo')
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
                            ->helperText('Only show photos that are not used in other sliders')
                            ->afterStateUpdated(fn($state, callable $set) => $set('preview', $state))
                            ->afterStateHydrated(fn($state, callable $set) => $set('preview', $state)),
                        Select::make('slide_number')
                            ->label('Slider Position')
                            ->options(array_combine(range(1, 10), range(1, 10)))
                            ->required()
                            ->disableOptionWhen(fn($value) => Slider::getUsedSliderNumber($value)),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->onIcon('heroicon-m-check-badge')
                            ->offIcon('heroicon-m-x-circle'),
                    ])->columns(2)
                    ->inlineLabel(),
                Section::make()->schema([
                    ViewField::make('preview')
                        ->view('filament.forms.components.photo-preview'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('slide_number')
                    ->label('Slider Position')
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => 'SLIDER 1',
                        2 => 'SLIDER 2',
                        3 => 'SLIDER 3',
                        4 => 'SLIDER 4',
                        5 => 'SLIDER 5',
                        6 => 'SLIDER 6',
                        7 => 'SLIDER 7',
                        8 => 'SLIDER 8',
                        9 => 'SLIDER 9',
                        10 => 'SLIDER 10',
                        default => 'UNKNOWN'
                    })
                    ->sortable(),
                ImageColumn::make('photo.file_path')
                    ->label('Photo')
                    ->disk('public')
                    ->square(),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-check-badge')
                    ->offIcon('heroicon-m-x-circle')
            ])->defaultSort('updated_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Sliders Found')
            ->emptyStateDescription('You have not created any sliders yet.')
            ->emptyStateIcon('heroicon-o-squares-2x2');
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
