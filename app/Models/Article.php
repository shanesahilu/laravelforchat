<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'author',
        'source_url',
        'published_at',
        'is_updated_version',  // flag to mark AI-updated articles
        'original_article_id', // links updated version to original
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_updated_version' => 'boolean',
    ];

    // get the original article if this is an updated version
    public function originalArticle()
    {
        return $this->belongsTo(Article::class, 'original_article_id');
    }

    // get updated versions of this article
    public function updatedVersions()
    {
        return $this->hasMany(Article::class, 'original_article_id');
    }
}
