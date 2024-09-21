<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisPembelianResource\Pages;
use App\Filament\Resources\JenisPembelianResource\RelationManagers;
use App\Models\JenisPembelian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\Actions\CreateAction;


class JenisPembelianResource extends Resource
{
    protected static ?string $model = JenisPembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

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
                                    ->label('Jenis Pembelian'),
                            ]),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->label('Terakhir'),

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
            'index' => Pages\ListJenisPembelians::route('/'),
            'create' => Pages\CreateJenisPembelian::route('/create'),
            'edit' => Pages\EditJenisPembelian::route('/{record}/edit'),
        ];
    }
}
