<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources;

use App\Filament\Clusters\WebsiteSettings;
use App\Filament\Clusters\WebsiteSettings\Resources\PageResource\Pages;
use App\Filament\Clusters\WebsiteSettings\Resources\PageResource\RelationManagers;
use App\Models\Content;
use App\Models\Customer;
use App\Models\Page;
use App\Models\Profil;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $cluster = WebsiteSettings::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Pages';
    protected static ?string $modelLabel = 'Pages';
    protected static ?string $pluralModelLabel = 'List Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('source_type')
                    ->label('Data Type')
                    ->options([
                        'App\Models\Profil' => 'Profil',
                        'App\Models\Customer' => 'Customer',
                        'App\Models\Content' => 'Content',
                    ])
                    ->required()
                    ->reactive(),
                Select::make('source_id')
                    ->label('Select Options')
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
                    })
                    ->reactive(),
                Select::make('style_view')
                    ->label('Style Page')
                    ->required()
                    ->options([
                        1 => 'Style 1',
                        // 2 => 'Style 2'
                    ])
                    ->default(1),
                Toggle::make('is_active')
                    ->label('Page is Active')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-badge')
                    ->offIcon('heroicon-o-x-circle'),
            ])->columns(1)
            ->inlineLabel();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('source_type')
                    ->label('Source Type')
                    ->formatStateUsing(fn($state): string => match ($state) {
                        'App\Models\Profil' => 'PROFIL',
                        'App\Models\Customer' => 'CUSTOMER',
                        'App\Models\Content' => 'CONTENT',
                        default => $state ?? '-',
                    }),
                TextColumn::make('source')
                    ->label('Source Data')
                    ->formatStateUsing(function ($record) {
                        if (!$record->source) return '-';

                        return match ($record->source_type) {
                            'App\Models\Profil' => $record->source->name_company,
                            'App\Models\Customer' => $record->source->name_customer,
                            'App\Models\Content' => $record->source->title,
                            default => '-',
                        };
                    }),
                TextColumn::make('style_view')
                    ->label('Style')
                    ->badge()
                    ->formatStateUsing(fn(Int $state): string => match ($state) {
                        1 => 'Style 1',
                        2 => 'Style 2',
                    })
                    ->color(fn(Int $state): string => match ($state) {
                        1 => 'success',
                        2 => 'primary'
                    }),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-check-badge')
                    ->offIcon('heroicon-m-x-circle')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('xl')
                    ->modalAutofocus(false),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['source']))
            ->emptyStateHeading('No Page Found')
            ->emptyStateDescription('Create a new page to get started')
            ->emptyStateIcon('heroicon-o-queue-list');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePages::route('/'),
        ];
    }
}
