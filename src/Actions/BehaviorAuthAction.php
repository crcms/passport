<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 13:54
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use CrCms\Foundation\App\Actions\ActionTrait;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Services\Behaviors\AbstractBehavior;
use CrCms\Passport\Services\Behaviors\BehaviorFactory;
use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class BehaviorAuthAction
 * @package CrCms\Passport\Actions
 */
class BehaviorAuthAction implements ActionContract
{
    use ActionTrait;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var Request
     */
    protected $request;

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
     * @param array $data
     * @return bool
     */
    public function handle(array $data = []): bool
    {
        $this->resolveDefaults($data);

        $user = $this->repository->byIntIdOrFail($this->request->input('user_id', 0));

        $this->behaviorService = $behaviorService = BehaviorFactory::factory($this->request->input('behavior_type'), $user, $this->request);

        return $behaviorService->validateRule($this->defaults['id']);
    }

    /**
     * @return AbstractBehavior
     */
    public function getBehaviorService(): AbstractBehavior
    {
        return $this->behaviorService;
    }

    /**
     * @param array $data
     * @return void
     */
    protected function resolveDefaults(array $data): void
    {
        $this->defaults['id'] = $data['id'] ?? 0;
        $this->defaults['fields'] = $data['fields'] ?? ['name', 'password'];
        $this->defaults['username'] = $data['username'] ?? 'name';
    }
}