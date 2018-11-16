<?php

namespace CrCms\Passport\Microservice\DataProviders;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Rules\ApplicationRule;

/**
 * Class LoginDataProvider
 * @package CrCms\Passport\Microservice\DataProviders
 */
class LoginDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $defaults = [
            'app_key' => ['required', app(ApplicationRule::class)],
            'ip' => ['ip'],
        ];

        return array_merge($defaults, config('passport.login_rules'));
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        $defaults = [
            'app_key' => trans('passport::app.auth.app_key'),
        ];

        $attributes = collect(config('passport.login_rules'))->keys()->mapWithKeys(function ($key) {
            return [$key => trans("passport::app.auth.{$key}")];
        })->toArray();

        return array_merge($defaults, $attributes);
    }
}