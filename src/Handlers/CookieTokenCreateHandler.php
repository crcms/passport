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
 * Class CookieTokenCreateHandler
 * @package CrCms\Passport\Handlers
 */
class CookieTokenCreateHandler extends AbstractHandler
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
     * CookieHandler constructor.
     * @param Request $request
     * @param UserModel $user
     * @param TokenContract $token
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
        $this->validateApplication();

        $application = $this->applicationRepository->byAppKeyOrFail($this->request->input('application_key'));

        /* @var UserModel */
        $user = $params[0];

        return $this->token->create($user, $application);
    }

    /**
     *
     */
    protected function validateApplication(): void
    {
        $this->validate($this->request, $this->validateRules());
    }

    /**
     * @return array
     */
    protected function validateRules(): array
    {
        $all = [
            'application_key' => ['required', Rule::exists((new ApplicationModel())->getTable(), 'app_key')]
        ];

        return $all;
    }
}