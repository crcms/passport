<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-12 12:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Repositories;

use CrCms\Foundation\App\Repositories\AbstractRepository;
use CrCms\Passport\Models\ApplicationModel;

/**
 * Class ApplicationRepository
 * @package CrCms\Passport\Repositories
 */
class ApplicationRepository extends AbstractRepository
{
    /**
     * @var array
     */
    protected $guard = ['app_key', 'app_secret', 'status'];

    /**
     * @return ApplicationModel
     */
    public function newModel(): ApplicationModel
    {
        return new ApplicationMOdel;
    }

    /**
     * @param string $key
     * @return ApplicationModel
     */
    public function byAppKeyOrFail(string $key): ApplicationModel
    {
        return $this->where('app_key', $key)->firstOrFail();
    }
}