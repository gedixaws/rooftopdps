<?php

use App\Exports\TemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Cache;
Route::get('/', function () {
    return view('welcome'); 
})->name('home');

Route::post('/check-transaction', [TransactionController::class, 'checkTransaction'])
    ->name('check-transaction');

Route::get('/transaction/{slug}', [TransactionController::class, 'show'])
    ->name('transaction.show');

Route::get('/invoice/{orderId}', [InvoiceController::class, 'generatePDF'])->name('invoice.pdf');

Route::get('/clear-cart/{orderId}', function ($orderId) {

    // Pastikan session dimulai terlebih dahulu
    if (session()->has('cart')) {
        session()->forget('cart');
        session()->save(); 
    }

    return redirect()->route('transaction.show', ['slug' => $orderId]); 
})->name('clear.cart');

