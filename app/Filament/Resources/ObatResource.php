<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObatResource\Pages;
use App\Filament\Resources\ObatResource\RelationManagers;
use App\Models\Obat;
use App\Models\MasterObat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Infolists\Components\MoneyEntry;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;

class ObatResource extends Resource
{
    protected static ?string $model = Obat::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationGroup = 'Produk';

    public static function form(Form $form): Form
    {
        $masterObats = MasterObat::get();
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->columns(6)
                    ->schema([
                        Forms\Components\Select::make('master_obat_id')
                            ->label('Master Obat')
                            ->relationship('masterObat', 'name')
                            // Options are all products, but we have modified the display to show the price as well
                            ->options(
                                $masterObats->mapWithKeys(function (MasterObat $obat) {
                                    return [$obat->id => sprintf('%s | %s | %s | %s', $obat->name, $obat->satuan->name, $obat->sediaan->name,  $obat->pt->name)];
                                })
                            )
                            // Disable options that are already selected in other rows
                            ->disableOptionWhen(function ($value, $state, Get $get) {
                                return collect($get('../*.master_obat_id'))
                                    ->reject(fn($id) => $id == $state)
                                    ->filter()
                                    ->contains($value);
                            })
                            ->afterStateUpdated(function (Get $get, Set $set,) {})
                            ->getSearchResultsUsing(function (string $search): array {
                                return MasterObat::query()
                                    ->where(function (Builder $builder) use ($search) {
                                        $searchString = "%$search%";
                                        $builder->where('name', 'like', $searchString);
                                    })
                                    // ->where('role_id', Role::CUSTOMER)
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function (MasterObat $master_obat) {
                                        return [$master_obat->id => $master_obat->name . " | " . $master_obat->satuan->name . " | " . $master_obat->sediaan->name . " | " . $master_obat->pt->name,];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->columnspan(4)
                            ->loadingMessage('Loading'),
                        DatePicker::make('tgl_expired')
                            ->columnspan(2)
                            ->required(),
                    ]),

                Forms\Components\Card::make()
                    ->columns(4)
                    ->schema([
                        TextInput::make('no_batch')
                            ->required()
                            ->label('Nomor Batch'),

                        TextInput::make('price')
                            ->prefix('Rp')
                            ->required(),


                        TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->label('Stock')
                            ->numeric(),


                        TextInput::make('margin')
                            ->prefix('Rp')
                            ->required()
                            ->label('Margin Keuntungan'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        // ->defaultSort('name');
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('masterObat.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->money('idr', locale: 'id')->sortable(),
                Tables\Columns\TextColumn::make('stock')->sortable(),
                Tables\Columns\TextColumn::make('margin')->money('idr', locale: 'id')->sortable(),
                Tables\Columns\TextColumn::make('masterObat.satuan.name')->sortable(),
                Tables\Columns\TextColumn::make('masterObat.sediaan.name')->sortable(),
                Tables\Columns\TextColumn::make('masterObat.pt.name')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->label('Terakhir'),
            ])
            ->filters([
                Filter::make('stock')
                    ->form([
                        Select::make('stock')
                        ->options([
                            'TERSEDIA' => 'TERSEDIA',
                            'KOSONG' => 'KOSONG',
                        ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query

                            ->when(
                                $data['stock'],
                                function (Builder $query, $val): Builder {
                                    // return $query->where('stock', '>', 1);
                                    // dd($val);

                                    if($val == "TERSEDIA"){
                                       return $query->where('stock', '>', 0);
                                    }

                                    if($val == "KOSONG"){
                                      return $query->where('stock', '<', 1);
                                    }

                                },
                            );
                    })
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
            'index' => Pages\ListObats::route('/'),
            'create' => Pages\CreateObat::route('/create'),
            'edit' => Pages\EditObat::route('/{record}/edit'),
        ];
    }
}
