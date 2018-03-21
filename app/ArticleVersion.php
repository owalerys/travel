<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class ArticleVersion extends Model implements HasMedia
{
    use HasMediaTrait;

    const STATUS_EDITING = 'editing';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_REJECTED = 'rejected';
    const STATUS_APPROVED = 'approved';
    const STATUS_LIVE = 'live';
    const STATUS_RETIRED = 'retired';

    /**
     * @var array valid statuses for article versions
     */
    public static $statuses = [
        self::STATUS_EDITING,
        self::STATUS_UNDER_REVIEW,
        self::STATUS_REJECTED,
        self::STATUS_APPROVED,
        self::STATUS_LIVE,
        self::STATUS_RETIRED
    ];

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
