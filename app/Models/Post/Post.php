<?php

namespace App\Models\Post;

use App\Models\BasicModel;
use App\Models\Constants\PostStatus;
use App\Models\Constants\Reaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class Post
 * @package App\Models
 *
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property int $status_id
 * @property int $totalReactions
 * @property int $totalLIKE
 * @property \Illuminate\Support\Carbon|null $published_at
 *
 * @property-read PostStatus $status
 * @property-read User $user
 */
class Post extends BasicModel
{
    protected $table = 'd_posts';

    protected $fillable = [
        'title', 'content', 'status_id'
    ];

    protected $dates = [
        'published_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Post $value) {
            $value->slug = Str::slug($value->title);
        });
    }

    public function reactions ()
    {
        return $this->hasMany(PostReaction::class, 'post_id', 'id');
    }

    public function status ()
    {
        return $this->hasOne(PostStatus::class, 'id', 'status_id');
    }

    public function user ()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select(['id', 'name']);
    }

    public function checkTotalReactions ($save = true, Reaction $reaction)
    {
        $this->totalReactions = $this->reactions()->count();
        $this->{'total' . $reaction->code} = $this->reactions()->where('reaction_id', '=', $reaction->id)->count();
        if ($save && $this->isDirty()) {
            $this->save();
        }
    }
}
