<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\App\Handlers\Traits\RequestHandlerTrait;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Services\Tokens\Contracts\TokenContract;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class CookieTokenHandler
 * @package CrCms\Passport\Handlers
 */
class CookieTokenHandler extends AbstractHandler
{
    use RequestHandlerTrait;

    /**
     * @var UserModel
     */
    protected $user;

    /**
     * @var TokenContract
     */
    protected $token;

    /**
     * @var
     */
    protected $applicationRepository;

    /**
     *
     */
    const TOKEN_CREATE = 'create';

    /**
     *
     */
    const TOKEN_INCREASE = 'increase';

    /**
     *
     */
    const TOKEN_DELETE = 'delete';

    /**
     * CookieTokenHandler constructor.
     * @param Request $request
     * @param TokenContract $token
     * @param ApplicationRepository $applicationRepository
     */
    public function __construct(Request $request, TokenContract $token, ApplicationRepository $applicationRepository)
    {
        $this->request = $request;
        $this->token = $token;
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * @param mixed ...$params
     * @return array
     */
    public function handle(...$params): array
    {
        $this->validateRule();

        $application = $this->applicationRepository->byAppKeyOrFail($this->request->input('app_key'));

        $action = array_shift($params);

        return call_user_func_array([$this->token, $action], array_merge($params, [$application]));
    }

    /**
     *
     */
    protected function validateRule(): void
    {
        $this->validate($this->request, [
            'app_key' => ['required', Rule::exists((new ApplicationModel())->getTable(), 'app_key')]
        ]);
    }
}