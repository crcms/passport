<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-09-07 11:00
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */


namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Services\CodeService;
use CrCms\Passport\Tasks\SendCodeTask;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * Class CodeHandler
 * @package CrCms\Passport\Handlers
 */
class CodeHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return string
     */
    public function handle(DataProviderContract $provider): string
    {
        $object = $provider->get('object');
        $type = $provider->get('type');
        try {
            $user = $this->app->make(UserRepository::class)->byMobileOrEmailOrFail($object);
        } catch (ResourceNotFoundException $exception) {
            throw ValidationException::withMessages(['user' => $exception->getMessage()]);
        }

        $code = $this->app->make(CodeService::class)->generate([$object, $type]);

        (new SendCodeTask)->handle($object, $user, $code, $type);

        return $code;
    }
}