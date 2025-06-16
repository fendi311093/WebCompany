<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources;

use App\Filament\Clusters\WebsiteSettings;
use App\Filament\Clusters\WebsiteSettings\Resources\NavigasiResource\Pages;
use App\Filament\Clusters\WebsiteSettings\Resources\NavigasiResource\RelationManagers;
use App\Models\HeaderButton;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class NavigasiResource extends Resource
{
    protected static ?string $model = HeaderButton::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-4';
    protected static ?string $cluster = WebsiteSettings::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Navigation';
    protected static ?string $navigationLabel = 'Navigation';
    protected static ?string $pluralModelLabel = 'List of Navigations';

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();
        $formData = HeaderButton::getFormData($record?->id);
        $pageOptions = HeaderButton::getPageOptionsOptimized();

        $allSourceIds = [];
        $usedSourceIds = [];
        foreach (['App\Models\Profil', 'App\Models\Customer', 'App\Models\Content'] as $type) {
            $allSourceIds[$type] = Page::getAllSourceIds($type);
            $usedSourceIds[$type] = HeaderButton::getPageOptionsOptimized($type);
        }

        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name_button')
                        ->label('Name Button')
                        ->unique(ignoreRecord: true)
                        ->rules(fn($record) => HeaderButton::getValidationRules($record)['name_button'])
                        ->validationMessages(HeaderButton::getValidationMessages()['name_button'])
                        ->dehydrateStateUsing(fn($state) => strtoupper($state))
                        ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state)))
                        ->live(onBlur: true),
                    TextInput::make('slug')
                        ->placeholder('auto generate from name button')
                        ->rules(fn($record) => HeaderButton::getValidationRules($record)['slug'])
                        ->validationMessages(HeaderButton::getValidationMessages()['slug'])
                        ->disabled()
                        ->dehydrated(),
                    Select::make('position')
                        ->label('Position')
                        ->placeholder('Please select position')
                        ->rules(fn($record) => HeaderButton::getValidationRules($record)['position'])
                        ->validationMessages(HeaderButton::getValidationMessages()['position'])
                        ->searchable()
                        ->disableOptionWhen(fn($value) => in_array($value, $formData['usedPositions']))
                        ->options([
                            1 => 'Nav Position 1',
                            2 => 'Nav Position 2',
                            3 => 'Nav Position 3',
                            4 => 'Nav Position 4',
                            5 => 'Nav Position 5',
                            6 => 'Nav Position 6',
                            7 => 'Nav Position 7',
                            8 => 'Nav Position 8',
                            9 => 'Nav Position 9',
                            10 => 'Nav Position 10',
                        ]),
                    Grid::make(1)->schema([
                        Select::make('page_id')
                            ->label('Page')
                            ->placeholder('Please select page')
                            ->rules(fn($record) => HeaderButton::getValidationRules($record)['page_id'])
                            ->validationMessages(HeaderButton::getValidationMessages()['page_id'])
                            ->options(function (callable $get) use ($allSourceIds) {
                                $type = $get('source_type');
                                return $allSourceIds[$type] ?? [];
                            })
                            ->searchable()
                            ->preload()
                            ->disableOptionWhen(function ($value, callable $get) use ($usedSourceIds) {
                                $type = $get('source_type');
                                return in_array($value, $usedSourceIds[$type] ?? []);
                            })
                            ->createOptionForm([
                                Select::make('source_type')
                                    ->label('Source Type')
                                    ->options([
                                        'App\Models\Profil' => 'Profil',
                                        'App\Models\Customer' => 'Customer',
                                        'App\Models\Content' => 'Content',
                                    ])
                                    ->required(),
                                Select::make('source_id')
                                    ->label('Select Page')
                                    ->preload()
                                    ->searchable()
                                    ->options(function (callable $get) {
                                        static $options = [];
                                        $type = $get('source_type');
                                        if (!isset($options[$type])) {
                                            $options[$type] = Page::getAllSourceIds($type);
                                        }
                                        return $options[$type];
                                    })
                                    ->required()
                                    ->disableOptionWhen(function ($value, callable $get) {
                                        static $used = [];
                                        $type = $get('source_type');
                                        if (!isset($used[$type])) {
                                            $used[$type] = Page::getUsedSourceIds($type);
                                        }
                                        return in_array($value, $used[$type]);
                                    }),
                                Select::make('style_view')
                                    ->label('Style Page')
                                    ->required()
                                    ->options([
                                        1 => 'Style 1',
                                        2 => 'Style 2'
                                    ])
                                    ->default(1),
                                Toggle::make('is_active')
                                    ->label('Page is Active')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->onIcon('heroicon-o-check-badge')
                                    ->offIcon('heroicon-o-x-circle'),
                            ])
                            ->createOptionUsing(fn(array $data) => Page::createPageFromNavigation($data)),
                        Toggle::make('is_active_button')
                            ->label('Active Button')
                            ->default(true)
                            ->onColor('primary')
                            ->offColor('danger')
                            ->onIcon('heroicon-o-check-badge')
                            ->offIcon('heroicon-o-x-circle'),
                    ]),
                ])->columns(3),
                Section::make()
                    ->description('Turn On adding URL to link to other website addresses')
                    ->icon('heroicon-m-information-circle')
                    ->iconColor('success')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('is_active_url')
                                ->label('Adding URL')
                                ->default(false)
                                ->onColor('primary')
                                ->offColor('danger')
                                ->onIcon('heroicon-o-check-badge')
                                ->offIcon('heroicon-o-x-circle')
                                ->reactive(),
                            TextInput::make('url')
                                ->label('URL')
                                ->rules(fn($record) => HeaderButton::getValidationRules($record)['url'])
                                ->validationMessages(HeaderButton::getValidationMessages()['url'])
                                ->suffixIcon('heroicon-m-globe-alt')
                                ->visible(fn(callable $get) => $get('is_active_url') == true)
                                ->required(fn(callable $get) => $get('is_active_url') == true),
                        ])
                            ->columns(1),
                    ])
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // mengambl data header button beserta data relasi Pages
        return parent::getEloquentQuery()->with(['Pages', 'Pages.source']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('name_button')
                    ->label("Name Button")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->formatStateUsing(fn($state) => 'Position' . ' - ' . $state),
                TextColumn::make('page_label') // atribut aksesori
                    ->label('Page'),
                IconColumn::make('is_active_url')
                    ->label('URL')
                    ->boolean(),
                ToggleColumn::make('is_active_button')
                    ->label('Active Button')
                    ->onIcon('heroicon-o-check-badge')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalAutofocus(false),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Navigations Button Found')
            ->emptyStateDescription('You can create a new navigation button by clicking the "Create" button Above.')
            ->emptyStateIcon('heroicon-o-list-bullet');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNavigasis::route('/'),
        ];
    }
}
