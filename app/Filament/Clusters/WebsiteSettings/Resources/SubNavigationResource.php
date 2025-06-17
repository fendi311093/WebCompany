<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources;

use App\Filament\Clusters\WebsiteSettings;
use App\Filament\Clusters\WebsiteSettings\Resources\SubNavigationResource\Pages;
use App\Filament\Clusters\WebsiteSettings\Resources\SubNavigationResource\RelationManagers;
use App\Models\DropdownMenu;
use App\Models\SubNavigation;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class SubNavigationResource extends Resource
{
    protected static ?string $model = DropdownMenu::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-down';
    protected static ?string $cluster = WebsiteSettings::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Dropdown Nav';
    protected static ?string $navigationLabel = 'Dropdown Nav';
    protected static ?string $pluralModelLabel = 'List of Dropdown Navigations';

    public static function form(Form $form): Form
    {
        $pageOptions = DropdownMenu::getPageOptions();
        $headerOptions = DropdownMenu::getHeaderOptions();

        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('title')
                        ->unique(ignoreRecord: true)
                        ->rules(fn($record): array => DropdownMenu::getValidationRules($record)['title'])
                        ->validationMessages(DropdownMenu::getValidationMessages()['title'])
                        ->dehydrateStateUsing(fn($state): string => strtoupper($state))
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')
                        ->placeholder('Auto Generated From Title')
                        ->rules(fn($record): array => DropdownMenu::getValidationRules($record)['slug'])
                        ->validationMessages(DropdownMenu::getValidationMessages()['slug'])
                        ->disabled()
                        ->dehydrated(),
                    Select::make('headerButton_id')
                        ->label('Header Button')
                        ->options($headerOptions)
                        // ->disableOptionWhen(fn($value, $record) => DropdownMenu::validateHeaderButton($value, $record))
                        ->rules(fn($record): array => DropdownMenu::getValidationRules($record)['headerButton_id'])
                        ->validationMessages(DropdownMenu::getValidationMessages()['headerButton_id'])
                        ->searchable()
                        ->preload()
                        ->placeholder('Select a header button')
                        ->reactive(),
                    Select::make('position')
                        ->placeholder('Select a position')
                        ->searchable()
                        ->rules(fn($record): array => DropdownMenu::getValidationRules($record)['position'])
                        ->validationMessages(DropdownMenu::getValidationMessages()['position'])
                        ->options([
                            '1' => 'Position 1',
                            '2' => 'Position 2',
                            '3' => 'Position 3',
                            '4' => 'Position 4',
                            '5' => 'Position 5',
                            '6' => 'Position 6',
                            '7' => 'Position 7',
                            '8' => 'Position 8',
                            '9' => 'Position 9',
                            '10' => 'Position 10',
                        ])
                        ->disableOptionWhen(function ($value, $record, callable $get) {
                            $headerButtonId = $get('headerButton_id');
                            if (!$headerButtonId) {
                                return false; // Jangan disable jika headerButton_id belum dipilih
                            }
                            return DropdownMenu::validatePosition($value, $record, ['headerButton_id' => $headerButtonId]);
                        })
                        ->visible(fn(callable $get) => filled($get('headerButton_id'))),
                ])->columns(2),
                Section::make()->schema([
                    Toggle::make('is_active')
                        ->label('Active Dropdown')
                        ->default(true)
                        ->onColor('primary')
                        ->offColor('danger')
                        ->onIcon('heroicon-o-check-badge')
                        ->offIcon('heroicon-o-x-circle')
                        ->reactive(),
                    Select::make('page_id')
                        ->label('Select Page')
                        ->placeholder('Select a page')
                        ->searchable()
                        ->preload()
                        ->rules(fn($record): array => DropdownMenu::getValidationRules($record)['page_id'])
                        ->validationMessages(DropdownMenu::getValidationMessages()['page_id'])
                        ->options($pageOptions)
                        ->disableOptionWhen(fn($value, $record) => DropdownMenu::validatePage($value, $record))
                        ->visible(fn(callable $get) => $get('is_active') == true),
                ]),
                Section::make()->schema([
                    Toggle::make('is_active_url')
                        ->label('Active URL')
                        ->default(false)
                        ->onColor('primary')
                        ->offColor('danger')
                        ->onIcon('heroicon-o-check-badge')
                        ->offIcon('heroicon-o-x-circle')
                        ->reactive(),
                    TextInput::make('url')
                        ->label('URL')
                        ->rules(fn($record): array => DropdownMenu::getValidationRules($record)['url'])
                        ->validationMessages(DropdownMenu::getValidationMessages()['url'])
                        ->suffixIcon('heroicon-m-globe-alt')
                        ->visible(fn(callable $get) => $get('is_active_url') == true)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('title'),
                TextColumn::make('headerButton.name_button')
                    ->label('Parent Header'),
                TextColumn::make('position')
                    ->formatStateUsing(fn($state) => 'POSITION - ' . $state),
                TextColumn::make('page_label') // Atribute aksesori getPageLabelAttribute
                    ->label('Page')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Page Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                IconColumn::make('is_active_url')
                    ->label('URL Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('url')
                    ->label('URL')
                    ->icon('heroicon-m-globe-alt')
                    ->default(fn($record) => $record->is_active_url ? $record->url : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->label('')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->size(ActionSize::Small)
                    ->Button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Dropdown Menus Found')
            ->emptyStateDescription('You can create a new dropdown menu by clicking the button above.')
            ->emptyStateIcon('heroicon-o-bars-arrow-down');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSubNavigations::route('/'),
        ];
    }

    // Untuk optimasi pengambilan data relasi Pages
    // dengan eager loading, sehingga mengurangi jumlah query yang dieksekusi
    // dan meningkatkan performa saat menampilkan data di tabel.
    // Ini akan mengurangi jumlah query yang dieksekusi saat mengambil data
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // mengambl data header button beserta data relasi Pages
        return parent::getEloquentQuery()->with(['Pages', 'Pages.source']);
    }
}
