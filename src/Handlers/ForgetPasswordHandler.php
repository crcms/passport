<?php

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Services\CodeService;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Validation\ValidationException;

/**
 * Class ForgetPasswordHandler
 * @package CrCms\Passport\Handlers
 */
class ForgetPasswordHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return string
     */
    public function handle(DataProviderContract $provider): string
    {
        $object = $provider->get('object');
        $code = $provider->get('code');

        if (!$this->app->make(CodeService::class)->check($object, $code)) {
            throw ValidationException::withMessages(['code' => 'Validate code error']);
        }

        return 'url';
    }
}