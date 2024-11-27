<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Penjualan;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;


class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {

        $obats = Obat::with('masterObat')->where('stock', '>', 0)->get();
        return $form
            ->columns(6)
            ->schema([


                Forms\Components\Card::make()
                    ->columnspan(4)
                    ->schema([
                        Forms\Components\Group::make()

                            ->schema([

                                Forms\Components\Repeater::make('penjualanItem')
                                    ->relationship()
                                    ->collapsible()
                                    ->cloneable()
                                    ->columns(5)
                                    ->live()
                                    // After adding a new row, we need to update the totals
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::updateTotals($get, $set);
                                    })
                                    // After deleting a row, we need to update the totals
                                    ->deleteAction(
                                        fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)),
                                    )
                                    // Disable reordering
                                    ->reorderable(false)
                                    ->schema([
                                        Forms\Components\Select::make('obat_id')
                                            ->label('obat')
                                            ->relationship('obat', 'name')
                                            // Options are all products, but we have modified the display to show the price as well
                                            ->options(
                                                $obats->mapWithKeys(function (Obat $obat) {
                                                    return [$obat->id => sprintf("%s\nSTOCK: %s\nNo Batch: %s\nExp %s", $obat->masterObat->name, $obat->stock, $obat->no_batch, date('d M Y', strtotime($obat->tgl_expired)))];
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
                                                if ($get('qty') != '' && $get('obat_id') != '') {
                                                    self::updateTotals($get, $set);
                                                    $prices = Obat::find($get('obat_id'));
                                                    // $set('obat.stock', $prices['stock'] - $get('qty'));
                                                    $set('total', ($prices['price'] + ($prices['price'] * ($prices['margin'] / 100))) * $get('qty'));
                                                    $set('price', $prices['price'] + ($prices['price'] * ($prices['margin'] / 100)));
                                                }
                                            })
                                            ->getSearchResultsUsing(function (string $search): array {
                                                return Obat::whereHas('masterObat', function (Builder $builder) use ($search) {
                                                    $searchString = "%$search%";
                                                    $builder->where('name', 'like', $searchString);
                                                    $builder->where('stock', '>', 0);
                                                })
                                                    ->limit(50)
                                                    ->get()
                                                    ->mapWithKeys(function (Obat $obat) {
                                                        return [$obat->id => sprintf("%s\nSTOCK: %s\nNo Batch: %s\nExp %s", $obat->masterObat->name, $obat->stock, $obat->no_batch, date('d M Y', strtotime($obat->tgl_expired)))];
                                                })
                                                    ->toArray();
                                            })
                                            ->searchable()
                                            ->required()
                                            ->reactive()
                                            ->columnspan(2)
                                            ->loadingMessage('Loading'),


                                        TextInput::make('qty')
                                            ->required()
                                            ->numeric()
                                            ->label('Qty')
                                            ->default(1)
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(function (Get $get, Set $set,) {
                                                if($get('obat_id') == ''){
                                                    return;
                                                }

                                                if($get('qty') != '') {
                                                    self::updateTotals($get, $set);
                                                    $prices = Obat::find($get('obat_id'));
                                                    // $set('obat.stock', $prices['stock'] - $get('qty'));
                                                    $set('total', ($prices['price'] + ($prices['price'] * ($prices['margin'] / 100))) * $get('qty'));
                                                    $set('price', $prices['price'] + ($prices['price'] * ($prices['margin'] / 100)));
                                                }
                                            })
                                            ->columnspan(1),

                                        // Hidden::make('obat.stock'),
                                        Hidden::make('price'),

                                        TextInput::make('total')
                                            ->required()
                                            ->mask(RawJs::make('$money($input)'))
                                            ->stripCharacters(',')
                                            ->prefix('Rp')
                                            ->live()
                                            ->readOnly()
                                            ->label('Subtotal')
                                            ->columnspan(2),

                                    ])
                                    ->defaultItems(1)
                                    ->reorderableWithButtons()
                                    ->reorderableWithDragAndDrop(),
                            ]),

                    ]),

                Forms\Components\Card::make()
                    ->columnspan(2)
                    ->schema([
                        Forms\Components\Group::make()
                            ->columns(1)
                            ->schema([
                                TextInput::make('subtotal')
                                    ->prefix('Rp')
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->live()
                                    ->readOnly()
                                    ->columnspan(1),

                                TextInput::make('discount')
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(debounce: 500)
                                    ->default(0)
                                    ->numeric()
                                    ->columnspan(1)
                                    ->afterStateUpdated(function (Get $get, Set $set) {

                                        self::updateTotals($get, $set);
                                    }),

                                TextInput::make('total')
                                    ->columnspan(1)
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->prefix('Rp')
                                    ->live()
                                    ->readOnly(),



                            ]),
                    ]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->columns(3)
                            ->schema([
                                TextInput::make('customer')->required()
                                    ->label('Customer')
                                    ->Default('-'),

                                Select::make('type')
                                    ->options([
                                        'UMUM' => 'UMUM',
                                        'KERJASAMA' => 'KERJASAMA',
                                    ])->default('UMUM'),

                                Textarea::make('keterangan')
                                    ->label('Keterangan'),


                            ]),
                    ]),

            ]);
    }

    public static function updateTotals(Get $get, Set $set): void
    {

        // Retrieve all selected products and remove empty rows
        $selectedProducts = collect($get('penjualanItem'))->filter(fn($item) => !empty($item['obat_id']) && !empty($item['qty']));

        // Retrieve prices for all selected products
        $prices = Obat::find($selectedProducts->pluck('obat_id'))->pluck('price', 'id');
        $margin = Obat::find($selectedProducts->pluck('obat_id'))->pluck('margin', 'id');
        // Calculate subtotal based on the selected products and quantities

        $subtotal = $selectedProducts->reduce(function ($subtotal, $product) use ($prices, $margin) {
            // dd($margin[$product['obat_id']]);
            // dd($product['obat_id']);

            return $subtotal + (($prices[$product['obat_id']] +   ($prices[$product['obat_id']] * ($margin[$product['obat_id']]/ 100))) * $product['qty']);
        }, 0);


        $subtotals = $subtotal;
        $set('subtotal', $subtotals);

        if ($get('discount') != '') {
            $total =  $subtotals - $get('discount');
            $set('total', $total);
        }
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->searchable()->label('ID Penj'),
                Tables\Columns\TextColumn::make('type')->sortable(),
                // Tables\Columns\TextColumn::make('penjualan_count')->counts('penjualan')->label('Item'),
                // Tables\Columns\TextColumn::make('subtotal')->money('idr', locale: 'id')->sortable(),
                // Tables\Columns\TextColumn::make('discount')->money('idr', locale: 'id')->sortable(),
                Tables\Columns\TextColumn::make('total')->money('idr', locale: 'id')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('D, d M Y')->sortable()->label('Tanggal'),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable()->label('Terakhir'),

            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('dari'),
                    DatePicker::make('sampai'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['dari'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['sampai'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
                    ->openUrlInNewTab()
                    ->url(fn($record) => route('print.penjualan.detail', $record->id))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
            // 'pdf' => Pages\ListObat::route('/obat'),
            // 'print' => ListPenjualan::route('/pdf'),
        ];
    }
}
