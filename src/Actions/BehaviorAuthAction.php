<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 13:54
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Services\Behaviors\AbstractBehavior;
use CrCms\Passport\Services\Behaviors\BehaviorFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class BehaviorAuthAction
 * @package CrCms\Passport\Actions
 */
class BehaviorAuthAction implements ActionContract
{
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
     * BehaviorAuthAction constructor.
     * @param Request $request
     * @param UserRepository $repository
     */
    public function __construct(Request $request, UserRepository $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    /**
     * @param Collection|null $collects
     * @return bool
     */
    public function handle(?Collection $collects = null)
    {
        $user = $this->repository->byIntIdOrFail($this->request->input('user_id', 0));

        $this->behaviorService = $behaviorService = BehaviorFactory::factory($this->request->input('behavior_type'), $user, $this->request);

        return $behaviorService->validateRule($collects->get('id', 0));
    }

    /**
     * @return AbstractBehavior
     */
    public function getBehaviorService(): AbstractBehavior
    {
        return $this->behaviorService;
    }
}