<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleVersion extends Model
{
    protected $table = 'article_versions';

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
