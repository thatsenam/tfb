
Route::get('/press-release/{blog}', [\App\Http\Controllers\MasterController::class, 'press_release'])->name('master.pr');
Route::get('/article/{blog}', [\App\Http\Controllers\MasterController::class, 'page_press_article'])->name('master.article');
Route::get('/{slug}', [\App\Http\Controllers\MasterController::class, 'run'])->name('master.run');
