<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\DrinkSize;
use Filament\Tables\Table;
use App\Models\FoodVariant;
use Illuminate\Support\Str;
use App\Models\OrderProduct;
use App\Models\PaymentMethod;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Info Utama')->schema([
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->default(fn() => Order::generateTransactionId())
                            ->readOnly(),
                        Forms\Components\Hidden::make('slug')
                            ->dehydrated(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(50),
                    ])
                ]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Info Tambahan')->schema([
                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ])
                ]),

                Forms\Components\Section::make('Produk Dipesan')->schema([
                    Repeater::make('items')
                        ->relationship('orderProducts')
                        ->live()
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Produk')
                                ->options(
                                    Product::whereNotNull('food_id')
                                        ->orWhereNotNull('drink_id')
                                        ->get()
                                        ->mapWithKeys(fn($product) => [
                                            $product->id => $product->food?->name ?? $product->drink?->name
                                        ])
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $product = Product::find($get('product_id'));

                                    if (!$product) return;

                                    // Cek apakah memiliki variant atau size
                                    $hasVariantOrSize = $product?->food?->variants()->exists() || $product?->drink?->sizes()->exists();

                                    if ($hasVariantOrSize) {
                                        // Jika ada variant/size, harga akan diatur setelah pemilihan variant/size
                                        $set('unit_price', 0);
                                    } else {
                                        // Jika tidak ada variant/size, langsung set harga dari produk
                                        $price = $product?->food?->price ?? $product?->drink?->price ?? 0;
                                        $set('unit_price', $price);
                                    }

                                    // Ambil stock dari product
                                    $set('stock', $product?->stock ?? 0);

                                    // **🔹 Tambahkan pemanggilan update total price DI AKHIR**
                                    self::updateTotalPrice($get, $set);
                                }),



                            Forms\Components\Select::make('food_variant_id')
                                ->label('Varian Makanan')
                                ->options(fn(Get $get) => FoodVariant::where('food_id', Product::find($get('product_id'))?->food_id)->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->reactive()
                                ->hidden(fn(Get $get) => Product::find($get('product_id'))?->food_id === null)
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $variant = FoodVariant::find($get('food_variant_id'));
                                    if ($variant) {
                                        $set('unit_price', $variant->price ?? 0);
                                    } else {
                                        $product = Product::find($get('product_id'));
                                        $set('unit_price', $product?->food->price ?? 0);
                                    }

                                    self::updateTotalPrice($get, $set);
                                }),

                            Forms\Components\Select::make('drink_size_id')
                                ->label('Drink Size')
                                ->options(fn(Get $get) => DrinkSize::where('drink_id', Product::find($get('product_id'))?->drink_id)
                                    ->pluck('size', 'id'))
                                ->searchable()
                                ->preload()
                                ->reactive()
                                ->hidden(fn(Get $get) => Product::find($get('product_id'))?->drink_id === null)
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $size = DrinkSize::find($get('drink_size_id'));
                                    if ($size) {
                                        $set('unit_price', $size->price ?? 0);
                                    } else {
                                        $product = Product::find($get('product_id'));
                                        $set('unit_price', $product?->drink->price ?? 0);
                                    }

                                    self::updateTotalPrice($get, $set);
                                }),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->placeholder('Masukkan jumlah (min 1)')
                                ->reactive()
                                ->live(debounce: 500)
                                ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                    $stock = $get('stock') ?? 0;

                                    if ($state > $stock) {

                                        $set('quantity', $stock);

                                        Notification::make()
                                            ->title('Stok tidak mencukupi')
                                            ->body("Jumlah pesanan melebihi stok yang tersedia ({$stock} tersisa).")
                                            ->warning()
                                            ->send();
                                    }

                                    self::updateTotalPrice($get, $set);
                                }),

                            Forms\Components\TextInput::make('stock')
                                ->label('Stock')
                                ->readOnly()
                                ->numeric(),

                            Forms\Components\TextInput::make('unit_price')
                                ->label('Harga Satuan')
                                ->numeric()
                                ->readOnly()
                                ->default(fn(Get $get) => $get('unit_price') ?? 0),
                        ])
                        ->columns(5)
                        ->afterStateUpdated(fn(Set $set, Get $get) => self::updateTotalPrice($get, $set)),
                ]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Harga')
                            ->required()
                            ->readOnly()
                            ->numeric()
                            ->prefix('IDR')
                            ->default(0)
                            ->live(),
                    ]),
                ]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Pembayaran')->schema([
                        Forms\Components\Select::make('payment_method_id')
                            ->relationship('paymentMethod', 'name')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $paymentMethod = PaymentMethod::find($state);
                                $set('is_cash', $paymentMethod?->is_cash ?? false);

                                if ($paymentMethod?->is_cash) {
                                    $set('change_amount', 0);
                                    $set('paid_amount', $get('total_price'));
                                }

                                $set('change_amount', 0);
                                $set('paid_amount', $get('total_price'));
                            })
                            ->afterStateHydrated(function (Set $set, Get $get, $state) {
                                $paymentMethod = PaymentMethod::find($state);
                                if ($paymentMethod?->is_cash) {
                                    $set('paid_amount', $get('total_price'));
                                    $set('change_amount', 0);
                                }
                                $set('is_cash', $paymentMethod?->is_cash ?? false);
                            }),

                        Forms\Components\Hidden::make('is_cash')->dehydrated(),

                        Forms\Components\TextInput::make('paid_amount')
                            ->numeric()
                            ->reactive()
                            ->label('Nominal Bayar')
                            ->readOnly(fn(Get $get) => !$get('is_cash'))
                            ->afterStateUpdated(fn(Set $set, Get $get, $state) => self::updateExchangePaid($get, $set)),

                        Forms\Components\TextInput::make('change_amount')
                            ->numeric()
                            ->label('Kembalian')
                            ->readOnly(),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id')->label('Transaction ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Customer')->sortable(),
                Tables\Columns\TextColumn::make('total_price')->label('Total Harga')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')->label('Metode Pembayaran')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dipesan Pada')->sortable()->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('invoice')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('invoice.pdf', $record->id))
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function calculateTotal(Get $get)
    {
        $items = $get('items') ?? [];
        $total = 0; // Pastikan total diinisialisasi dengan 0

        foreach ($items as $item) {
            // Ambil harga yang sudah tersimpan di dalam field 'price'
            $price = $item['unit_price'] ?? 0;
            $quantity = $item['quantity'] ?? 1;

            $total += $price * $quantity;
        }

        return $total;
    }

    public static function updateExchangePaid(Get $get, Set $set)
    {
        $change = max(0, ($get('paid_amount') ?? 0) - ($get('total_price') ?? 0));
        $set('change_amount', $change);
    }

    protected static function updateTotalPrice(Get $get, Set $set)
    {
        $totalPrice = self::calculateTotal($get);
        $set('total_price', $totalPrice);
    }

    // public static function getItemsRepeater(): Repeater
    // {
    //     return Repeater::make('orderProducts')
    //         ->relationship()
    //         ->live()
    //         ->columns([
    //             'md' => 10,
    //         ])
    //         ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
    //             self::updateTotalPrice($get, $set);
    //         })
    //         ->schema([
    //             Forms\Components\Select::make('product_id')
    //                 ->label('Produk')
    //                 ->required()
    //                 ->options(Product::query()->where('stock', '>', 1)->pluck('name', 'id'))
    //                 ->columnSpan([
    //                     'md' => 5
    //                 ])
    //                 ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, $state) {
    //                     $product = Product::find($state);
    //                     $set('unit_price', $product->price ?? 0);
    //                     $set('stock', $product->stock ?? 0);
    //                 })
    //                 ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
    //                     $product = Product::find($state);
    //                     $set('unit_price', $product->price ?? 0);
    //                     $set('stock', $product->stock ?? 0);
    //                     $quantity = $get('quantity' ?? 1);
    //                     $stock = $get('stock');
    //                     self::updateTotalPrice($get, $set);
    //                 })
    //                 ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
    //             Forms\Components\Select::make('serving_type')
    //                 ->required()
    //                 ->options([
    //                     'hot' => 'Hot',
    //                     'cold' => 'Cold',
    //                 ])
    //                 ->columnSpan([
    //                     'md' => 5
    //                 ]),
    //             Forms\Components\TextInput::make('quantity')
    //                 ->required()
    //                 ->numeric()
    //                 ->default(1)
    //                 ->minValue(1)
    //                 ->columnSpan([
    //                     'md' => 1
    //                 ])
    //                 ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
    //                     $stock = $get('stock');
    //                     if ($state > $stock) {
    //                         $set('quantity', $stock);
    //                         Notification::make()
    //                             ->title('Stok Tidak Mencukupi')
    //                             ->warning()
    //                             ->send();
    //                     }

    //                     self::updateTotalPrice($get, $set);
    //                 }),
    //             Forms\Components\TextInput::make('stock')
    //                 ->required()
    //                 ->numeric()
    //                 ->readOnly()
    //                 ->columnSpan([
    //                     'md' => 1
    //                 ]),
    //             Forms\Components\TextInput::make('unit_price')
    //                 ->label('Harga Saat ini')
    //                 ->required()
    //                 ->numeric()
    //                 ->readOnly()
    //                 ->columnSpan([
    //                     'md' => 3
    //                 ]),
    //         ]);
    // }

    // public static function updateTotalPrice(Forms\Get $get, Forms\Set $set): void
    // {
    // $selectedProducts = collect($get('orderProducts'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity']));

    // $prices = Product::find($selectedProducts->pluck('product_id'))->pluck('price', 'id');
    // $total = $selectedProducts->reduce(function ($total, $product) use ($prices) {
    //     return $total + ($prices[$product['product_id']] * $product['quantity']);
    // }, 0);

    // $set('total_price', $total);
    // }

    // protected static function updateExchangePaid(Forms\Get $get, Forms\Set $set): void
    // {
    //     $paidAmount = (int) $get('paid_amount') ?? 0;
    //     $totalPrice = (int) $get('total_price') ?? 0;
    //     $exchangePaid = $paidAmount - $totalPrice;
    //     $set('change_amount', $exchangePaid);
    // }
}
