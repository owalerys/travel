<?php

namespace App;

use Carbon\Carbon;
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

    public function createFirstVersion(array $config)
    {
        if ($this->versions()->count()) {
            return $this->versions()->first();
        }

        $version = new ArticleVersion;

        $version->title = $this->display_title;
        $version->description = $this->display_description;
        $version->type = $this->type;
        $version->schema_version = $this->schema_version;
        $version->category_slug = $this->category_slug;
        $version->create_date = Carbon::now('UTC')->format('Y-m-d H:i:s');

        foreach ($config as $key => $value) {
            if ($value) {
                $version->{$key} = $value;
            }
        }

        $this->versions()->save($version);

        $this->versions;

        return $version;
    }
}
