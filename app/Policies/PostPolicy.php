<?php

namespace App\Policies;

use App\Models\Constants\PostStatus;
use App\Models\User;
use App\Models\Post\Post;

class PostPolicy
{
    /**
     * Only author can edit
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function edit (User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }

    /**
     * Pubslihed - visisble for everyone, but DRAFT only for author
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function view (User $user, Post $post)
    {
        return ($post->status->code == PostStatus::ST_PUBLISHED || $user->id === $post->user_id);
    }
}
