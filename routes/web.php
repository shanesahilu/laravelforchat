<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'BeyondChats Articles API',
        'version' => '1.0.0',
        'endpoints' => [
            'GET /api/articles' => 'List all articles',
            'GET /api/articles/latest' => 'Get latest original article',
            'GET /api/articles/{id}' => 'Get article by ID or slug',
            'POST /api/articles' => 'Create new article',
            'PUT /api/articles/{id}' => 'Update article',
            'DELETE /api/articles/{id}' => 'Delete article',
        ]
    ]);
});
