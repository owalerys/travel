<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 07/02/18
 * Time: 11:41 AM
 */

namespace App\Services;


use App\Airline;
use App\Article;
use App\Contracts\Topical;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class ArticleManagementService
{

    public function __construct()
    {

    }

    /**
     * @param string|null $title
     * @param string $categorySlug
     * @param string $schemaVersion
     * @param string $type
     * @param Topical|null $subject
     * @param string|null $description
     * @return Article
     */
    protected function createNewArticle(string $title = null, string $categorySlug, string $schemaVersion, string $type, Topical $subject = null, string $description = null)
    {
        $article = new Article;

        $article->category_slug = $categorySlug;
        $article->display_description = $description;
        $article->display_title = $title;
        $article->type = $type;
        $article->schema_version = $schemaVersion;

        if ($subject) {
            $article->topic()->associate($subject);
        }

        $article->save();

        return $article;
    }

    /**
     * @param string $title
     * @param string $categorySlug
     * @param string $version
     * @param string $type
     * @param string|null $url
     * @param Authenticatable $user
     * @param Topical|null $subject
     * @param string|null $description
     * @return Article
     */
    public function createNewArticleWithFirstVersion(string $title = null, string $categorySlug, string $version, string $type, string $url = null, Authenticatable $user, Topical $subject = null, string $description = null)
    {
        $article = $this->createNewArticle($title, $categorySlug, $version, $type, $subject, $description);

        $version = $article->createFirstVersion([
            'url' => $url
        ]);

        $version->author()->associate($user);

        $version->save();

        return $article;
    }

}