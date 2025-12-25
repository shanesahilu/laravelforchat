<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // get all articles with optional filtering
    public function index(Request $request): JsonResponse
    {
        $query = Article::query();

        // filter by type: original, updated, or all
        if ($request->has('type')) {
            if ($request->type === 'original') {
                $query->where('is_updated_version', false);
            } elseif ($request->type === 'updated') {
                $query->where('is_updated_version', true);
            }
        }

        // include relationships if requested
        if ($request->boolean('with_versions')) {
            $query->with(['updatedVersions', 'originalArticle']);
        }

        $articles = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $articles,
            'count' => $articles->count()
        ]);
    }

    // get single article by id or slug
    public function show(string $identifier): JsonResponse
    {
        $article = Article::where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->with(['updatedVersions', 'originalArticle'])
            ->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    // create new article
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'author' => 'nullable|string|max:100',
            'source_url' => 'nullable|url',
            'published_at' => 'nullable|date',
            'is_updated_version' => 'nullable|boolean',
            'original_article_id' => 'nullable|exists:articles,id',
        ]);

        // auto-generate slug from title
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);

        // set defaults
        $validated['is_updated_version'] = $validated['is_updated_version'] ?? false;
        $validated['published_at'] = $validated['published_at'] ?? now();

        $article = Article::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article created successfully',
            'data' => $article
        ], 201);
    }

    // update existing article
    public function update(Request $request, int $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'excerpt' => 'nullable|string',
            'author' => 'nullable|string|max:100',
            'source_url' => 'nullable|url',
            'published_at' => 'nullable|date',
        ]);

        // regenerate slug if title changed
        if (isset($validated['title']) && $validated['title'] !== $article->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        }

        $article->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully',
            'data' => $article->fresh()
        ]);
    }

    // delete article
    public function destroy(int $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
        }

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ]);
    }

    // get latest article (for the node script)
    public function latest(): JsonResponse
    {
        $article = Article::where('is_updated_version', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'No articles found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }
}
