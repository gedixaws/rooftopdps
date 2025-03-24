<?php

use App\Exports\TemplateExport;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/download-template', function(){
    return Excel::download(new TemplateExport, 'template.xlsx');
})->name('download-template');

Route::get('/invoice/{orderId}', [InvoiceController::class, 'generatePDF'])->name('invoice.pdf');

