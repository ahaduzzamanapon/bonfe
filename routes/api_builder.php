<?php


Route::resource('suppliers', 'SupplierAPIController');

Route::resource('payment_methods', 'PaymentMethodAPIController');

Route::resource('account_ledgers', 'AccountLedgerAPIController');

Route::resource('petty_cashes', 'PettyCashAPIController');

Route::resource('occupations', 'OccupationAPIController');

Route::resource('programs', 'ProgramAPIController');

Route::resource('upazilas', 'UpazilaAPIController');