<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    public function versions()
    {
        return $this->hasMany(ArticleVersion::class, 'article_id');
    }

    public function topic()
    {
        return $this->morphTo();
    }

    public function createFirstVersion()
    {
        if ($this->versions()->count()) {
            return $this->versions()->first();
        }

        $version = new ArticleVersion([
            'title' => $this->title,
            'description' => $this->description
        ]);

        $this->versions()->save($version);

        return $version;
    }
}
