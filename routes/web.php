<?php

use App\Exports\TemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome'); 
})->name('home');

Route::post('/check-transaction', [TransactionController::class, 'checkTransaction'])
    ->name('check-transaction');

Route::get('/transaction/{slug}', [TransactionController::class, 'show'])
    ->name('transaction.show');

Route::get('/download-template', function(){
    return Excel::download(new TemplateExport, 'template.xlsx');
})->name('download-template');

Route::get('/invoice/{orderId}', [InvoiceController::class, 'generatePDF'])->name('invoice.pdf');

