<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 12:05
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Tasks;

use CrCms\Foundation\App\Tasks\AbstractTask;
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Repositories\Contracts\TokenContract;

/**
 * Class TokenTask
 * @package CrCms\Passport\Tasks
 */
class TokenTask extends AbstractTask
{
    /**
     * @var TokenContract
     */
    protected $token;

    /**
     * @var ApplicationRepository
     */
    protected $repository;

    /**
     *
     */
    const TOKEN_NEW = 'new';

    /**
     *
     */
    const TOKEN_INCREASE = 'increase';

    /**
     *
     */
    const TOKEN_DELETE = 'remove';

    /**
     *
     */
    const TOKEN_REFRESH = 'refresh';

    /**
     *
     */
    const TOKEN_REMOVE = 'remove';

    /**
     * CookieTokenHandler constructor.
     * @param TokenContract $token
     * @param ApplicationRepository $repository
     */
    public function __construct(TokenContract $token, ApplicationRepository $repository)
    {
        $this->token = $token;
        $this->repository = $repository;
    }

    /**
     *
     * @example self 75 handle($action, $appKey, .....)
     * @param mixed ...$params
     * @return array
     */
    public function handle(...$params): array
    {
        list($action, $appKey) = $params;

        array_shift($params);

        $application = $this->repository->byAppKeyOrFail($appKey);
        $params[array_search($appKey, $params)] = $application;

        return call_user_func_array([$this->token, $action], $params);
    }
}