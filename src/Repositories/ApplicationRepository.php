<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-12 12:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Repositories;

use CrCms\Repository\AbstractRepository;
use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
     *
     * @return ApplicationModel
     */
    public function byAppKeyOrFail(string $key): ApplicationModel
    {
        return $this->where('app_key', $key)->firstOrFail();
    }

    /**
     * @param ApplicationModel $application
     *
     * @return Collection
     */
    public function domainsByApplication(ApplicationModel $application): Collection
    {
        return $application->belongsToManyDomain()->get();
    }

    /**
     * applicationUserPaginate
     *
     * @param ApplicationModel $application
     * @param int $currentPage
     * @return LengthAwarePaginator
     */
    public function applicationUserPaginate(ApplicationModel $application,int $currentPage): LengthAwarePaginator
    {
        return $application->belongsToManyUser()->orderBy('id', 'desc')->paginate(null, ['*'], 'page', $currentPage);
    }
}
