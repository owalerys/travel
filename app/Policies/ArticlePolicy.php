<?php

namespace App\Policies;

use App\Article;
use App\ArticleVersion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view the articleVersion.
     *
     * @param  \App\User  $user
     * @param  \App\ArticleVersion  $articleVersion
     * @return mixed
     */
    public function view(User $user, Article $article)
    {
        return $user->hasPermissionTo('view articles');
    }

    /**
     * Determine whether the user can create articleVersions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create articles');
    }

    /**
     * Determine whether a user can archive an article
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function archive(User $user, Article $article)
    {
        $anyLive = false;
        $notAuthor = false;

        foreach ($article->versions as $version) {
            if ($version->status === ArticleVersion::STATUS_LIVE) {
                $anyLive = true;
            }

            if ($version->author->id !== $user->id) {
                $notAuthor = true;
            }
        }

        $canManage = $user->hasPermissionTo('manage articles');
        $canEdit = $user->hasPermissionTo('edit articles');

        return !$anyLive && (($canEdit && !$notAuthor) || $canManage);
    }

    /**
     * Determine whether a user can activate an article
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function activate(User $user, Article $article)
    {
        $canEdit = $user->hasPermissionTo('edit articles');
        $canManage = $user->hasPermissionTo('manage articles');

        return $canEdit || $canManage;
    }
}
