<?php

namespace CrCms\Passport\Models;

use CrCms\Foundation\Models\Model;

/**
 * Class TokenModel
 * @package CrCms\Passport\Models
 */
class TokenModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'passport_tokens';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string
     */
    protected $primaryKey = 'token';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $casts = [
        'applications' => 'array',
    ];
}