<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleVersionComment extends Model
{
    protected $table = 'article_version_comments';

    /**
     * Refers to user that wrote comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commenter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Refers to current author at time of comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function version()
    {
        return $this->belongsTo(ArticleVersion::class, 'article_version_id');
    }
}
