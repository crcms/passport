<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-11 22:59
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers\Cookie;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\App\Handlers\Traits\RequestHandlerTrait;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Services\Tokens\Contracts\TokenContract;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class UpdateHandler
 * @package CrCms\Passport\Handlers\Cookie
 */
class UpdateHandler extends AbstractHandler
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

        $token = $this->request->cookie('token');
        $appKey = $this->request->input('app_key');

        if (empty($token) || empty($appKey)) {
            return [];
        }

        $tokens = $this->token->get($token);

        if (in_array($appKey, $tokens['applications'], true)) {
            return [];
        }

        return $this->token->increase($token, $this->applicationRepository->byAppKeyOrFail($appKey));
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