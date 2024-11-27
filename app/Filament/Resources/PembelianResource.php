<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Filament\Resources\PembelianResource\RelationManagers;
use App\Models\Obat;
use App\Models\MasterObat;
use App\Models\Supplier;
use App\Models\Pembelian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Infolists\Components\MoneyEntry;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\RawJs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;


class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Transaksi';
    public static function form(Form $form): Form
    {
        $products = Obat::get();
        $supplier = Supplier::get();
        $masterObats = MasterObat::get();
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('supplier_id')
                                    ->relationship('supplier', 'name')
                                    // Options are all products, but we have modified the display to show the price as well
                                    ->options(
                                        $supplier->mapWithKeys(function (Supplier $supplier) {
                                            return [$supplier->id => $supplier->name];
                                        })
                                    )
                                    ->getSearchResultsUsing(function (Builder $builder, string $search) {
                                        return Supplier::where('name', 'like', "%$search%")
                                            ->limit(50)
                                            ->get()
                                            ->mapWithKeys(function (Supplier $supplier) {
                                                return [$supplier->id => $supplier->name];
                                            })
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->loadingMessage('Loading'),


                                Forms\Components\Select::make('jenis_pembelian_id')
                                    ->relationship(
                                        name: 'jenisPembelian',
                                        titleAttribute: 'name',
                                    )->required(),


                                TextInput::make('no_faktur')->required()
                                    ->label('Nomor Faktur'),

                                TextInput::make('no_faktur_pajak')->required()
                                    ->label('Nomor Faktur Pajak'),

                            ]),

                    ]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->columns(3)
                            ->schema([

                                Forms\Components\DatePicker::make('tgl_diterima')->required(),

                                Forms\Components\DatePicker::make('tgl_faktur')->required(),

                                Forms\Components\DatePicker::make('tgl_jatuh_tempo')->required(),

                            ]),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()

                            ->schema([
                                Forms\Components\Repeater::make('obat')
                                    ->relationship()
                                    ->collapsible()
                                    ->cloneable()
                                    ->columns(5)
                                    ->cloneAction(function (Action $action) {
                                        return $action
                                            ->after(function ($component, $get, $state) {
                                                self::updateTotal($component);
                                            });
                                    })
                                    ->deleteAction(function (Action $action) {
                                        return $action
                                            ->after(function ($component, $get, $state) {
                                                self::updateTotal($component);
                                            });
                                    })
                                    ->schema([
                                        Forms\Components\Select::make('master_obat_id')
                                            ->label('Master Obat')
                                            ->relationship('masterObat', 'name')
                                            // Options are all products, but we have modified the display to show the price as well
                                            ->options(
                                                $masterObats->mapWithKeys(function (MasterObat $obat) {
                                                    return [$obat->id => sprintf("%s\n%s\n%s\n%s", $obat->name, $obat->satuan->name, $obat->sediaan->name,  $obat->pt->name)];
                                                })
                                            )
                                            // Disable options that are already selected in other rows
                                            ->disableOptionWhen(function ($value, $state, Get $get) {
                                                return collect($get('../*.master_obat_id'))
                                                    ->reject(fn($id) => $id == $state)
                                                    ->filter()
                                                    ->contains($value);
                                            })

                                            ->getSearchResultsUsing(function (string $search): array {
                                                return MasterObat::query()
                                                    ->where(function (Builder $builder) use ($search) {
                                                        $searchString = "%$search%";
                                                        $builder->where('name', 'like', $searchString);
                                                        // ->orWhere('email', 'like', $searchString)
                                                        // ->orWhere('phone', 'like', $searchString);
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

                                        Forms\Components\DatePicker::make('tgl_expired')->required(),

                                        TextInput::make('no_batch')->required()
                                            ->label('Nomor Batch'),

                                        TextInput::make('margin')
                                            ->suffix('%')
                                            ->numeric()
                                            ->default(0)
                                            ->required()
                                            ->live(onBlur: true)
                                            ->label('Margin Keuntungan'),

                                        TextInput::make('pembelian_price')
                                            ->prefix('Rp')
                                            ->numeric()
                                            ->default(0)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, Set $set, TextInput $component) {

                                                if ($get('pembelian_price') != "" && $get('pembelian_stock') != "") {
                                                    // dd("ADA ISINYA");

                                                    $set('price', $get('pembelian_price'));
                                                    $set('pembelian_total', $get('pembelian_price') * $get('pembelian_stock'));
                                                    self::updateTotal($component);
                                                }
                                            })
                                            ->required(),


                                        TextInput::make('pembelian_stock')
                                            ->required()
                                            ->label('Stock')
                                            ->numeric()
                                            ->default(0)
                                            // ->live(onBlur:true)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, Set $set, TextInput $component) {

                                                if ($get('pembelian_price') != "" && $get('pembelian_stock') != "") {

                                                    $set('stock', $get('pembelian_stock'));
                                                    $set('pembelian_total', $get('pembelian_price') * $get('pembelian_stock'));
                                                    self::updateTotal($component);
                                                }
                                            })
                                            ->disabled(fn(Get $get): bool => blank($get('price'))),

                                        Hidden::make('price'),
                                        Hidden::make('stock'),

                                        // TextInput::make('price'),
                                        // TextInput::make('stock'),

                                        TextInput::make('pembelian_total')
                                            ->prefix('Rp')
                                            ->required()
                                            ->numeric()
                                            ->mask(RawJs::make('$money($input)'))
                                            ->stripCharacters(',')
                                            ->readonly()
                                            ->label('Subtotal'),

                                    ])
                                    ->defaultItems(1)
                                    ->reorderableWithButtons()
                                    ->reorderableWithDragAndDrop(),
                            ]),



                    ]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()

                            ->schema([

                                // TextInput::make('total')
                                //     ->mask(RawJs::make('$money($input)'))
                                //     ->stripCharacters(',')
                                //     ->prefix('Rp')
                                //     ->numeric(),

                                TextInput::make('total')
                                    ->prefix('Rp')
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->readOnly()
                                    ->numeric()
                                    ->afterStateHydrated(function (Get $get, Set $set, TextInput $component) {
                                        self::updateTotal($component);
                                    }),


                                // MoneyInput::make('total')
                                //     ->required()
                                //     ->currency('IDR')
                                //     ->live()
                                //     ->locale('id_ID')
                                //     ->label('Total'),

                                Textarea::make('keterangan')
                                    ->required()
                                    ->label('Keterangan'),


                            ]),
                    ]),


            ]);
    }

    private static function updateTotal(Field $component): void
    {
        $livewire = $component->getLivewire();
        $collect =  collect(data_get($livewire, 'data.obat'));

        $subtotal = $collect->reduce(function ($subtotal, $obat) {
            // dd($obat);

            // if ($obat['pembelian_price'] == "" || $obat['pembelian_stock'] == "") {
            //     dd("ISO");
            return $subtotal + ($obat['pembelian_price'] * $obat['pembelian_stock']);
            // }


        }, 0);
        // dd($subtotal);
        // data_set($livewire, 'data.subtotal', $subtotal);
        data_set($livewire, 'data.total', $subtotal);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('no_faktur')->sortable()->searchable(),
                // Tables\Columns\TextColumn::make('tgl_diterima')->dateTime('D M Y')->sortable(),
                Tables\Columns\TextColumn::make('tgl_faktur')->dateTime('D, d M Y')->sortable(),
                Tables\Columns\TextColumn::make('tgl_jatuh_tempo')->dateTime('D, d M Y')->sortable(),
                Tables\Columns\TextColumn::make('obat_count')->counts('obat'),
                Tables\Columns\TextColumn::make('total')->money('idr', locale: 'id')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('D, d M Y')->label('Dibuat')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable()->label('Terakhir'),
            ])
            ->filters([
                Filter::make('tgl_faktur')
                    ->form([
                        DatePicker::make('dari'),
                        DatePicker::make('sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tgl_faktur', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tgl_faktur', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->color('success')
                    ->icon('heroicon-o-printer')
                    ->requiresConfirmation(true)
                    // ->action(fn() => Redirect::away('pdf'))
                    ->openUrlInNewTab()
                    ->url(fn($record) => route('print.pembelian.detail', $record->id))
                    ->openUrlInNewTab(),

                // Tables\Actions\DeleteAction::make()
                //     ->requiresConfirmation(true),


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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'DESC');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}
