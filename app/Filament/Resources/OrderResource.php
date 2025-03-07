<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\OrderProduct;
use App\Models\PaymentMethod;
use Filament\Resources\Resource;
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
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Info Utama')
                    ->Schema([
                        Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(50),
                    ])
                ]),
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Info Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('note')
                        ->columnSpanFull(),
                    ])
                ]),
                Forms\Components\Section::make('Product Dipesan')->schema([
                    self::getItemsRepeater(),
                ]),

                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                    ->Schema([
                        Forms\Components\TextInput::make('total_price')
                        ->required()
                        ->readOnly()
                        ->numeric(),
                        ])
                    ]),
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Pembayaran')
                    ->Schema([
                        Forms\Components\Select::make('payment_method_id')
                        ->relationship('paymentMethod', 'name')
                        ->reactive()
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            $paymentMethod = PaymentMethod::find($state);
                            $set('is_cash', $paymentMethod?->is_cash ?? false);

                            if($paymentMethod?->is_cash) {
                                $set('change_amount', 0);
                                $set('paid_amount', $get('total_price'));
                            }

                            $set('change_amount', 0);
                            $set('paid_amount', $get('total_price'));
                        })
                        ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, $state) {
                            $paymentMethod = PaymentMethod::find($state);
                            if($paymentMethod?->is_cash) {
                                $set('paid_amount', $get('total_price'));
                                $set('change_amount', 0);
                            }
                            $set('is_cash', $paymentMethod?->is_cash ?? false);
                        }),
                        Forms\Components\Hidden::make('is_cash')
                        ->dehydrated(),
                        Forms\Components\TextInput::make('paid_amount')
                        ->numeric()
                        ->reactive()
                        ->label('Nominal Bayar')
                        ->readOnly(fn (Forms\Get $get) => !$get('is_cash'))
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                            //Function Untuk Menghitung Uang Kembali
                            self::updateExchangePaid($get, $set);
                        }),
                        Forms\Components\TextInput::make('change_amount')
                        ->numeric()
                        ->label('Kembalian')
                        ->readOnly(),
                        ])
                    ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('change_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('orderProducts')
        ->relationship()
        ->live()
        ->columns([
            'md'=> 10,
        ])
        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
            self::updateTotalPrice($get, $set);
        })
        ->schema([
            Forms\Components\Select::make('product_id')
            ->label('Produk')
            ->required()
            ->options(Product::query()->where('stock', '>', 1)->pluck('name', 'id'))
            ->columnSpan([
                'md'=> 5
            ])
            ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, $state) {
                $product = Product::find($state);
                $set('unit_price', $product->price ?? 0);
                $set('stock', $product->stock ?? 0);
            })
            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                $product = Product::find($state);
                $set('unit_price', $product->price ?? 0);
                $set('stock', $product->stock ?? 0);
                $quantity = $get('quantity' ?? 1);
                $stock = $get('stock');
                self::updateTotalPrice($get, $set);
            })
            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
            Forms\Components\Select::make('serving_type')
            ->required()
            ->options([
                'hot' => 'Hot',
                'cold' => 'Cold',
            ])
            ->columnSpan([
                'md'=> 5
            ]),
            Forms\Components\TextInput::make('quantity')
            ->required()
            ->numeric()
            ->default(1)
            ->minValue(1)
            ->columnSpan([
                'md'=> 1
            ])
            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                $stock = $get('stock');
                if($state > $stock){
                    $set('quantity', $stock);
                    Notification::make()
                    ->title('Stok Tidak Mencukupi')
                    ->warning()
                    ->send();
                }

                self::updateTotalPrice($get, $set);
            }),
            Forms\Components\TextInput::make('stock')
            ->required()
            ->numeric()
            ->readOnly()
            ->columnSpan([
                'md'=> 1
            ]),
            Forms\Components\TextInput::make('unit_price')
            ->label('Harga Saat ini')
            ->required()
            ->numeric()
            ->readOnly()
            ->columnSpan([
                'md'=> 3
            ]),
        ]);
    }

    public static function updateTotalPrice(Forms\Get $get, Forms\Set $set): void
    {
        $selectedProducts = collect($get('orderProducts'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity']));

        $prices = Product::find($selectedProducts->pluck('product_id'))->pluck('price','id');
        $total = $selectedProducts->reduce(function ($total, $product) use ($prices) {
            return $total + ($prices[$product['product_id']] * $product['quantity']);
        }, 0);

        $set('total_price', $total);

    }

    protected static function updateExchangePaid(Forms\Get $get, Forms\Set $set): void
    {
        $paidAmount = (int) $get('paid_amount') ?? 0;
        $totalPrice = (int) $get('total_price') ?? 0;
        $exchangePaid = $paidAmount - $totalPrice;
        $set('change_amount', $exchangePaid);
    }
 
}
