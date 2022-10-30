<?php

namespace App\Models\Constants;

use App\Models\BasicModel;

/**
 * Class PostStatus
 * @package App\Models
 *
 * @property string $code
 */
class PostStatus extends BasicModel
{
    const ST_DRAFT = 'DRAFT';
    const ST_PUBLISHED = 'PUBLISHED';

    protected $table = 'c_post_statuses';

    protected $fillable = [
        'code'
    ];
}
