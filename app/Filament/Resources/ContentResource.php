<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    // protected static ?string $cluster = WebsiteSettings::class;
    // protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $navigationLabel = 'Contents';
    protected static ?string $modelLabel = 'Content';
    protected static ?string $pluralModelLabel = 'List Contents';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 3;

    // Di Filament, method ini digunakan untuk menemukan record berdasarkan ID di URL
    public static function resolveRecordRouteBinding(int|string $key): ?Model
    {
        return static::getModel()::findByHashedId($key);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('title')
                        ->unique(ignoreRecord: true)
                        ->rules(fn($record) => Content::getValidationRules($record)['title'])
                        ->validationMessages(Content::getValidationMessages()['title'])
                        ->dehydrateStateUsing(fn($state) => strtoupper($state))
                        ->afterStateUpdated(fn($set, $state) => $set('slug', Str::slug($state)))
                        ->live(onBlur: true),
                    TextInput::make('slug')
                        ->disabled()
                        ->dehydrated(),
                    MarkdownEditor::make('description')
                        ->rules(fn($record) => Content::getValidationRules($record)['description'])
                        ->validationMessages(Content::getValidationMessages()['description'])
                        ->disableToolbarButtons(['link', 'attachFiles']),
                    FileUpload::make('photo')
                        ->image()
                        ->imageEditor()
                        ->required()
                        ->maxSize(11000)
                        ->directory('contents')
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record, $get): string {
                            $contentName = $get('title') ?? ($record?->title ?? 'Photo');
                            return Content::generateSafeFileName($contentName, $file);
                        }),
                    Toggle::make('is_active')
                        ->label('Card is Active')
                        ->inline()
                        ->default(true)
                        ->onColor('info')
                        ->offColor('danger')
                        ->onIcon('heroicon-o-check-badge')
                        ->offIcon('heroicon-o-x-circle'),
                ])->columns(1)
                    ->columnSpanFull()
                    ->inlineLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->searchable()
                    ->description(fn(Content $record): string => Str::limit($record->description, 50)),
                ImageColumn::make('photo'),
                ToggleColumn::make('is_active')
                    ->label('Card is Active')
                    ->onIcon('heroicon-o-check-badge')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateHeading('No Contents Found')
            ->emptyStateDescription('You have not created any contents yet. Click the button above to create one.')
            ->emptyStateIcon('heroicon-o-computer-desktop');
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
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
