<?php

namespace App\Models\Constants;

use App\Models\BasicModel;

/**
 * Class Reaction
 * @package App\Models
 *
 * @property string $code
 */
class Reaction extends BasicModel
{
    protected $table = 'c_reactions';

    protected $fillable = [
        'code'
    ];
}
