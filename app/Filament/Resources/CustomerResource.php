<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Customer';
    protected static ?string $pluralLabel = 'List Customers';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name_customer')
                    ->label('Customer')
                    ->autocapitalize('characters')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->rules(fn($record) => Customer::getValidationRules($record)['name_customer'])
                    ->validationMessages(Customer::getValidationMessages()),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->required()
                    ->maxSize(11000)
                    ->image()
                    ->imageEditor()
                    ->directory('customer_logo')
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record, $get): string {
                        // Ambil nama perusahaan dari input atau data record
                        $profilName = $get('name_customer') ?? ($record?->name_customer ?? 'Customer');

                        // Ubah ke huruf besar lalu slug agar rapi dan aman
                        $upperName = strtoupper($profilName);
                        $safeName = \Illuminate\Support\Str::slug($upperName);
                        $safeName = \Illuminate\Support\Str::limit($safeName, 50, '');

                        // Validasi dan pastikan hanya ekstensi gambar tertentu yang diizinkan
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                        $guessed = $file->guessExtension();
                        $extension = in_array($guessed, $allowedExtensions) ? ($guessed === 'jpeg' ? 'jpg' : $guessed) : 'jpg';

                        // Tambahkan timestamp agar nama unik
                        $timestamp = now()->format('dmy-His');

                        return "{$safeName}-{$timestamp}.{$extension}";
                    }),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->inline(false)
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-badge')
                    ->offIcon('heroicon-o-x-circle'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('name_customer')
                    ->label('Customer')
                    ->copyable()
                    ->searchable(),
                ImageColumn::make('logo'),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-badge')
                    ->offIcon('heroicon-o-x-circle'),
            ])->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalAutofocus(false),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No posts yet')
            ->emptyStateDescription('Once you create a customer, it will appear here')
            ->emptyStateIcon('heroicon-o-user-group');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
