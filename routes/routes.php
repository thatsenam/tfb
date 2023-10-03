use App\Http\Controllers\MasterController;

Route::get('/press-release/{blog}', [MasterController::class, 'press_release'])->name('master.pr');
Route::get('/article/{blog}', [MasterController::class, 'page_press_article'])->name('master.article');
Route::get('/{slug}', [MasterController::class, 'run'])->name('master.run');
