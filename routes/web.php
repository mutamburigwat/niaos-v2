<?php

use App\Models\Quotation;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/app'));
Route::get('/admin', fn () => redirect('/app'));
Route::get('/admin/dashboard', fn () => redirect('/app'));
Route::get('/admin/login', fn () => redirect('/app/login'));

Route::get('/quotations/{quotation}/print', function (Quotation $quotation) {
    return view('filament.pages.print-quotation', compact('quotation'));
})->name('quotations.print');
