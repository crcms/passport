<?php

namespace CrCms\Passport\Models;

use CrCms\Foundation\App\Models\Model;

/**
 * Class TokenModel
 * @package CrCms\Passport\Models
 */
class TokenModel extends Model
{
    protected $table = 'passport_tokens';

    public $timestamps = false;

    protected $keyType = 'string';

    protected $primaryKey = 'token';
}