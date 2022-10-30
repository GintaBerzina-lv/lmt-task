<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Mail\PostReactionNotification;
use App\Models\Constants\PostStatus;
use App\Models\Constants\Reaction;
use App\Models\Post\Post;
use App\Models\Post\PostReaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PostController extends Controller
{

    /**
     * Build query for list (viw & api). Add filters if needed
     *
     * @param Request $request
     * @param false $withUser
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function listQuery (Request $request, $withUser = false)
    {
        $qry = Post::query()
            ->where(function ($q) use ($request, $withUser) {
                $q->whereHas('status', function ($q2) {
                    $q2->where('code', '=', PostStatus::ST_PUBLISHED);
                });
                if ($withUser && $request->user()) {
                    $q->orWhere('user_id', '=', $request->user()->id);
                }
            })
            ->orderBy('published_at', 'desc')
            ->with(['user', 'status']);

        if ($withUser && $request->user()) {
            $qry->with('reactions', function ($q) use ($request) {
                $q->where('user_id', '=', $request->user()->id);
            });
        }
        return $qry;
    }

    public function listapi (Request $reqeust)
    {
        $qry = $this->listQuery($reqeust);
        $qry->limit(5);

        return PostResource::collection($qry->get());
    }

    /**
     * Paged list of posts
     * @param Request $request
     * @return \Inertia\Response
     */
    public function list (Request $request)
    {
        return Inertia::render('Post/List/List', [
            'posts' => $this->listQuery($request, true)->paginate(10),
            'reactions' => Reaction::select(['id', 'code'])->get()
        ]);
    }

    /**
     * View new create form
     *
     * @return \Inertia\Response
     */
    public function viewnew ()
    {
        $post = new Post();
        foreach ($post->getFillable() as $field) {
            $fields[$field] = null;
        }
        $statuses = PostStatus::select(['id', 'code'])->get();
        if (count($statuses) > 0) {
            $fields['status_id'] = $statuses[0]->id;
        }
        return Inertia::render('Post/View/View', ['post' => $fields, 'statuses' => $statuses]);
    }

    /**
     * Delete post - only if author
     *
     * @param Request $request
     * @param Post $post
     * @return \Inertia\Response
     */
    public function delete (Request $request, Post $post)
    {
        $this->authorize('edit', $post);
        $post->delete();
        return $this->list($request);
    }

    /**
     * View post:
     *
     * @param Request $request
     * @param Post $post
     * @return \Inertia\Response
     */
    public function view (Request $request, Post $post)
    {
        $this->authorizeForUser(($request->user() ? $request->user() : new User), 'view', $post);
        $post->load('user');
        if ($request->user()) {
            $post->load(['reactions' => function ($q) use ($request) {
                $q->where('user_id', '=', $request->user()->id);
            }]);
        };
        return Inertia::render('Post/View/View', [
            'post' => $post,
            'statuses' => PostStatus::select(['id', 'code'])->get(),
            'reactions' => Reaction::select(['id', 'code'])->get()
        ]);
    }

    /**
     * Update/create existing post. Only if is author
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function edit (Request $request)
    {
        $request->validate([
            'id' => ['nullable'],
            'title' => ['required', 'max:255'],
            'content' => [],
            'status_id' => ['exists:' . PostStatus::class . ',id']
        ]);

        /** @var Post $post */
        $post = Post::query()->firstOrNew(['id' => $request->input('id')]);
        if ($post->id) {
            $this->authorize('edit', $post);
        }
        $post->fill($request->input());
        $post->user_id = $request->user()->id;
        if (empty($post->published_at) && $post->status->code == PostStatus::ST_PUBLISHED) {
            $post->published_at = Carbon::now();
        }
        $post->save();

        if ($post->wasRecentlyCreated) {
            return redirect()->intended('posts/view/' . $post->id);
        }

        return $this->view($request, $post);
    }

    public function react(Request $request, Post $post)
    {
        $request->validate([
            'reaction_id' => ['exists:' . Reaction::class . ',id']
        ]);
        $updateData = [
            'post_id' => $post->id,
            'reaction_id' => $request->input('reaction_id'),
            'user_id' => $request->user()->id
        ];
        /** @var PostReaction $postReact */
        $postReact = PostReaction::withTrashed()
            ->with(['post'])
            ->firstOrNew($updateData);

        if ($request->input('delete')) {
            if ($postReact->id && is_null($postReact->deleted_at)) {
                $postReact->delete();
            }
        } else {
            $postReact->deleted_at = null;
            if ($postReact->isDirty()) {
                $postReact->save();

                // Send notification, that liked. Maybe should add check to not send if self-liked
                $user = User::find($post->user_id);
                if ($user && $user->email) {
                    Mail::to($user)
                        ->queue(new PostReactionNotification($post));
                }
            }
        }
    }
}
