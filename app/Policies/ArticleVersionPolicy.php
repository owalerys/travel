<?php

namespace App\Policies;

use App\User;
use App\ArticleVersion;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticleVersionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the articleVersion.
     *
     * @param  \App\User  $user
     * @param  \App\ArticleVersion  $articleVersion
     * @return mixed
     */
    public function view(User $user, ArticleVersion $articleVersion)
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
     * Determine whether the user can update the articleVersion.
     *
     * @param  \App\User  $user
     * @param  \App\ArticleVersion  $articleVersion
     * @return mixed
     */
    public function edit(User $user, ArticleVersion $articleVersion)
    {
        $canEditPermission = $user->hasPermissionTo('edit articles');
        $isAuthor = $user->id === $articleVersion->author->id;
        $inEditMode = $articleVersion->status === ArticleVersion::STATUS_EDITING;

        return $canEditPermission && $isAuthor && $inEditMode;
    }

    /**
     * Determine whether the user can archive the articleVersion.
     *
     * @param  \App\User  $user
     * @param  \App\ArticleVersion  $articleVersion
     * @return mixed
     */
    public function archive(User $user, ArticleVersion $articleVersion)
    {
        $canEditPermission = $user->hasPermissionTo('edit articles');
        $isAuthor = $user->id === $articleVersion->author->id;
        $inEditMode = $articleVersion->status === ArticleVersion::STATUS_EDITING;

        return $canEditPermission && $isAuthor && $inEditMode;
    }

    /**
     * Determine whether the user can retire the article version
     *
     * @param User $user
     * @param ArticleVersion $articleVersion
     * @return bool
     */
    public function retire(User $user, ArticleVersion $articleVersion)
    {
        $canRetirePermission = $user->hasPermissionTo('retire articles');
        $inLiveMode = $articleVersion->status === ArticleVersion::STATUS_RETIRED;

        return $canRetirePermission && $inLiveMode;
    }

    /**
     * Determine whether the user can set to review the article version
     *
     * @param User $user
     * @param ArticleVersion $articleVersion
     * @return bool
     */
    public function setReview(User $user, ArticleVersion $articleVersion)
    {
        $canEditPermission = $user->hasPermissionTo('edit articles');
        $isAuthor = $user->id === $articleVersion->author->id;
        $inEditMode = $articleVersion->status === ArticleVersion::STATUS_EDITING;

        return $canEditPermission && $isAuthor && $inEditMode;
    }

    /**
     * Determine whether the user can review the article version
     *
     * @param User $user
     * @param ArticleVersion $articleVersion
     * @return bool
     */
    public function review(User $user, ArticleVersion $articleVersion)
    {
        $canReviewPermission = $user->hasPermissionTo('review articles');
        $isAuthor = $user->id === $articleVersion->author->id;
        $canReviewOwnPermission = $user->hasPermissionTo('review own articles');
        $inReviewMode = $articleVersion->status === ArticleVersion::STATUS_UNDER_REVIEW;

        return $inReviewMode && ((!$isAuthor && $canReviewPermission) || ($isAuthor && $canReviewOwnPermission));
    }

    /**
     * Determine whether the user can manage an article version's uploaded files
     *
     * @param User $user
     * @param ArticleVersion $articleVersion
     * @return bool
     */
    public function upload(User $user, ArticleVersion $articleVersion)
    {
        $canEditPermission = $user->hasPermissionTo('edit articles');
        $isAuthor = $user->id === $articleVersion->author->id;
        $inEditMode = $articleVersion->status === ArticleVersion::STATUS_EDITING;

        return $canEditPermission && $isAuthor && $inEditMode;
    }

    /**
     * Determine whether the user can download files from an article
     *
     * @param User $user
     * @param ArticleVersion $articleVersion
     * @return bool
     */
    public function download(User $user, ArticleVersion $articleVersion)
    {
        $canViewPermission = $user->hasPermissionTo('view articles');

        return $canViewPermission;
    }

    /**
     * Determine whether the user can publish an article version
     *
     * @param User $user
     * @param ArticleVersion $articleVersion
     * @return bool
     */
    public function publish(User $user, ArticleVersion $articleVersion)
    {
        $canPublishPermission = $user->hasPermissionTo('publish articles');
        $isApproved = $articleVersion->status === ArticleVersion::STATUS_APPROVED;

        return $canPublishPermission && $isApproved;
    }
}
