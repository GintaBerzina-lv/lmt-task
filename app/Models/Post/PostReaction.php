<?php

namespace App\Models\Post;

use App\Models\BasicModel;
use App\Models\Constants\PostStatus;
use App\Models\Constants\Reaction;
use App\Models\User;

/**
 * Class PostReaction
 * @package App\Models
 *
 * @property int $user_id
 * @property int $post_id
 * @property int $reaction_id
 *
 * @property-read Post $post
 * @property-read Reaction $reaction
 * @property-read User $user
 */
class PostReaction extends BasicModel
{
    protected $table = 'd_post_reactions';

    protected $fillable = [
        'user_id', 'post_id', 'reaction_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (PostReaction $value) {
            $value->post->checkTotalReactions(true, $value->reaction);
        });

        static::deleted(function (PostReaction $value) {
            $value->post->checkTotalReactions(true, $value->reaction);
        });
    }

    public function reaction ()
    {
        return $this->hasOne(Reaction::class, 'id', 'reaction_id');
    }

    public function post ()
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function user ()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
