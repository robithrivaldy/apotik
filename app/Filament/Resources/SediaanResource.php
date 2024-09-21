<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SediaanResource\Pages;
use App\Filament\Resources\SediaanResource\RelationManagers;
use App\Models\Sediaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;

class SediaanResource extends Resource
{
    protected static ?string $model = Sediaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Master';
    public static function form(Form $form): Form
    {
        return $form
            ->columns(5)
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                TextInput::make('name')->required()
                                    ->label('Nama Sediaan'),
                            ]),


                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable()->label('Terakhir'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation(),
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
            'index' => Pages\ListSediaans::route('/'),
            'create' => Pages\CreateSediaan::route('/create'),
            'edit' => Pages\EditSediaan::route('/{record}/edit'),
        ];
    }
}
