<?php

use Illuminate\Support\Facades\Route;
use Modules\Dictionary\Http\Controllers\DictionaryController;

Route::middleware(['auth', 'verified'])->group(function () {
});
