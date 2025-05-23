<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $navigationLabel = 'Contents';
    protected static ?string $navigationGroup = 'Website Settings';
    protected static ?int $navigationSort = 23;
    protected static ?string $modelLabel = 'Content';
    protected static ?string $pluralLabel = 'List Contents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->rules(fn($record) => Content::getValidationRules($record)['title'])
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),
                MarkdownEditor::make('description')
                    ->required()
                    ->disableToolbarButtons(['link', 'attachFiles']),
                FileUpload::make('photo')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->maxSize(11000)
                    ->directory('contents')
                    ->getUploadedFileNameForStorageUsing(
                        function (TemporaryUploadedFile $file, $record, $get): string {
                            $contentName = $get('title') ?? ($record?->title ?? 'Content');
                            $saveName = \Illuminate\Support\Str::slug($contentName);
                            $extension = $file->getClientOriginalExtension();
                            return "{$saveName}." . $extension;
                        }
                    ),
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true)
                    ->onColor('info')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-badge')
                    ->offIcon('heroicon-o-x-circle')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->searchable(),
                ImageColumn::make('photo')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContents::route('/'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
