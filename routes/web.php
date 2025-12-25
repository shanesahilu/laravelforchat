<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'BeyondChats Articles API',
        'version' => '1.0.0',
        'endpoints' => [
            'GET /api/articles' => 'List all articles',
            'GET /api/articles/latest' => 'Get latest article',
            'GET /api/articles/{id}' => 'Get single article',
            'POST /api/articles' => 'Create article',
            'PUT /api/articles/{id}' => 'Update article',
            'DELETE /api/articles/{id}' => 'Delete article',
            'GET /api/health' => 'Health check',
        ]
    ]);
});
