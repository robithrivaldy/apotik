<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockOpnameResource\Pages;
use App\Filament\Resources\StockOpnameResource\RelationManagers;
use App\Models\StockOpname;
use App\Models\Obat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\RawJs;

class StockOpnameResource extends Resource
{
    protected static ?string $model = StockOpname::class;

    protected static ?string $navigationIcon = 'heroicon-o-window';
    protected static ?string $navigationGroup = 'Transaksi';
    public static function form(Form $form): Form
    {
        $obats = Obat::get();
        return $form
            ->columns(6)
            ->schema([
                Forms\Components\Card::make()

                    ->schema([
                        Forms\Components\Group::make()
                            ->columns(4)
                            ->schema([

                                Forms\Components\Select::make('obat_id')
                                    ->label('Obat')
                                    ->relationship('obat', 'name')
                                    // Options are all products, but we have modified the display to show the price as well
                                    ->options(
                                        $obats->mapWithKeys(function (Obat $obat) {
                                            return [$obat->id => sprintf("%s\nSTOCK: %s\nNo: %s\nExp %s", $obat->masterObat->name, $obat->stock, $obat->no_batch,  date('d M Y', strtotime($obat->tgl_expired)))];
                                        })
                                    )
                                    // Disable options that are already selected in other rows
                                    ->disableOptionWhen(function ($value, $state, Get $get) {
                                        return collect($get('../*.obat_id'))
                                            ->reject(fn($id) => $id == $state)
                                            ->filter()
                                            ->contains($value);
                                    })
                                    ->afterStateUpdated(function (Get $get, Set $set,) {
                                        $stock = Obat::find($get('obat_id'));
                                        // dd($prices);
                                        // $margin = Obat::find($get('obat_id'))->pluck('margin', 'id');
                                        $set('stock_awal', $stock['stock']);
                                        $set('stock_akhir', $stock['stock']);
                                    })
                                    ->getSearchResultsUsing(function (string $search): array {
                                        return Obat::whereHas('masterObat', function (Builder $builder) use ($search) {
                                            $searchString = "%$search%";
                                            $builder->where('name', 'like', $searchString);
                                        })
                                            ->limit(50)
                                            ->get()
                                            ->mapWithKeys(function (Obat $obat) {
                                                return [$obat->masterObat->id => $obat->masterObat->name . " | STOCK " . $obat->stock . " | No : " . $obat->no_batch];
                                            })
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->columnspan(2)
                                    ->loadingMessage('Loading'),

                                TextInput::make('stock_awal')
                                    ->required()
                                    ->numeric()
                                    ->columnspan(1)
                                    ->label('Stock Awal')
                                    ->default(0),

                                TextInput::make('stock_akhir')
                                    ->required()
                                    ->numeric()
                                    ->columnspan(1)
                                    ->label('Qty')
                                    ->label('Stock Akhir')
                                    ->default(0)->afterStateUpdated(function (Get $get, Set $set,) {
                                        $set('stock', $get('stock_akhir'));
                                    }),

                                Hidden::make('stock'),


                            ]),

                    ]),


                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()

                            ->schema([


                                Textarea::make('keterangan')
                                    ->label('Keterangan'),


                            ]),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('keterangan')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('obatColumn.masterObat.name')->sortable()->searchable()->label('Nama Obat'),
                Tables\Columns\TextColumn::make('stock_awal')->sortable()->searchable()->label('Stock Awal'),
                Tables\Columns\TextColumn::make('stock_akhir')->sortable()->searchable()->label('Stock Akhir'),
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
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStockOpnames::route('/'),
            'create' => Pages\CreateStockOpname::route('/create'),
            'edit' => Pages\EditStockOpname::route('/{record}/edit'),
        ];
    }
}
