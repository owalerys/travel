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

class ArticleManagementService
{

    public function __construct()
    {

    }

    /**
     * @param string $title
     * @param string $categorySlug
     * @param Topical|null $subject
     * @param string|null $description
     * @return Article
     */
    protected function createNewArticle(string $title, string $categorySlug, Topical $subject = null, string $description = null)
    {
        $article = new Article([
            'article' => $title,
            'category_slug' => $categorySlug,
            'description' => $description
        ]);

        if ($subject) {
            $article->topic()->associate($subject);
        }

        $article->save();

        return $article;
    }

    /**
     * @param Article $article
     * @return $this|\App\ArticleVersion
     */
    protected function createFirstVersion(Article $article)
    {
        return $article->createFirstVersion();
    }

    /**
     * @param string $title
     * @param string $categorySlug
     * @param User $user
     * @param Topical|null $subject
     * @param string|null $description
     * @return Article
     */
    public function createNewArticleWithFirstVersion(string $title, string $categorySlug, User $user, Topical $subject = null, string $description = null)
    {
        $article = $this->createNewArticle($title, $categorySlug, $subject, $description);

        $version = $this->createFirstVersion($article);

        $version->author()->associate($user);

        $version->save();

        return $article;
    }

}