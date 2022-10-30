<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $values = [
            'id' => $this->id,
            'link' => url("posts/view/{$this->id}"),
            'title' => $this->title,
            'content' => $this->content,
            'totalReactions' => $this->totalReactions,
            'totalLIKE' => $this->totalLIKE,
            'published_at' => $this->published_at,
            'username' => $this->user->name,
            'status' => $this->status->code
        ];

        return $values;
    }
}
