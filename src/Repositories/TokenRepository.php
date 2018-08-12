<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-12 12:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Repositories;

use CrCms\Foundation\App\Repositories\AbstractRepository;
use CrCms\Passport\Models\TokenModel;

/**
 * Class TokenRepository
 * @package CrCms\Passport\Repositories
 */
class TokenRepository extends AbstractRepository
{
    /**
     * @var array
     */
    protected $guard = ['token', 'user_id', 'applications', 'expired_at'];

    /**
     * @return TokenModel
     */
    public function newModel(): TokenModel
    {
        return new TokenModel;
    }
}