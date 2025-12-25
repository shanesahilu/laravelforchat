<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes are prefixed with /api automatically
*/

// Health check for debugging
Route::get('/health', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'error: ' . $e->getMessage();
    }
    
    return response()->json([
        'status' => 'ok',
        'php' => PHP_VERSION,
        'laravel' => app()->version(),
        'database' => $dbStatus,
        'env' => config('app.env'),
    ]);
});

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);           // GET /api/articles
    Route::get('/latest', [ArticleController::class, 'latest']);    // GET /api/articles/latest
    Route::get('/{identifier}', [ArticleController::class, 'show']); // GET /api/articles/{id|slug}
    Route::post('/', [ArticleController::class, 'store']);          // POST /api/articles
    Route::put('/{id}', [ArticleController::class, 'update']);      // PUT /api/articles/{id}
    Route::delete('/{id}', [ArticleController::class, 'destroy']);  // DELETE /api/articles/{id}
});
