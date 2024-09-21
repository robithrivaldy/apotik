<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterObatResource\Pages;
use App\Filament\Resources\MasterObatResource\RelationManagers;
use App\Models\MasterObat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterObatResource extends Resource
{
    protected static ?string $model = MasterObat::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Produk';
    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Card::make()
                    ->columnSpan(3)
                    ->schema([


                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Nama Obat'),





                        Forms\Components\Select::make('satuan')
                            ->relationship(
                                name: 'satuan',
                                titleAttribute: 'name',
                            )->required(),
                        Forms\Components\Select::make('sediaan')
                            ->relationship(
                                name: 'sediaan',
                                titleAttribute: 'name',
                            )->required(),

                        Forms\Components\Select::make('pt')
                            ->relationship(
                                name: 'pt',
                                titleAttribute: 'name',
                            )->required(),


                        // Forms\Components\Select::make('supplier')
                        //     ->relationship(
                        //         name: 'supplier',
                        //         titleAttribute: 'name',
                        //     )->required(),

                    ]),
                // TextInput::make('name')->required(),
                // TextInput::make('no_batch')->required(),
                // TextInput::make('margin')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {


        // ->defaultSort('name');
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('satuan.name')->sortable(),
                Tables\Columns\TextColumn::make('sediaan.name')->sortable(),
                Tables\Columns\TextColumn::make('pt.name')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable()->label('Terakhir'),

                // Tables\Columns\TextColumn::make('supplier.name')->sortable(),
                // ...
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


    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['created_by'] = auth()->id();

    //     return $data;
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterObats::route('/'),
            'create' => Pages\CreateMasterObat::route('/create'),
            'edit' => Pages\EditMasterObat::route('/{record}/edit'),
        ];
    }
}
