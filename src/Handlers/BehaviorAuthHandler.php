<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 13:54
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Handlers\Traits\RepositoryHandlerTrait;
use CrCms\Foundation\Handlers\Traits\RequestHandlerTrait;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Services\Behaviors\AbstractBehavior;
use CrCms\Passport\Services\Behaviors\BehaviorFactory;
use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class BehaviorAuthHandler
 * @package CrCms\Passport\Handlers
 */
class BehaviorAuthHandler extends AbstractHandler
{
    use RequestHandlerTrait, RepositoryHandlerTrait;

    /**
     * @var AbstractBehavior
     */
    protected $behaviorService;

    /**
     * @var Config
     */
    protected $config;

    /**
     * BehaviorAuthAction constructor.
     * @param Request $request
     * @param UserRepository $repository
     * @param Config $config
     */
    public function __construct(Request $request, UserRepository $repository, Config $config)
    {
        $this->request = $request;
        $this->repository = $repository;
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function handle(): bool
    {
        $user = $this->repository->byIntIdOrFail($this->request->input('user_id', 0));

        $this->behaviorService = $behaviorService = BehaviorFactory::factory($this->request->input('behavior_type'), $user, $this->request);

        return $behaviorService->validateRule($this->request->route()->parameter('behavior_id'));
    }

    /**
     * @return AbstractBehavior
     */
    public function getBehaviorService(): AbstractBehavior
    {
        return $this->behaviorService;
    }
}