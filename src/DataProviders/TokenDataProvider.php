<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:38
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\DataProviders;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Rules\ApplicationRule;

/**
 * Class TokenDataProvider
 * @package CrCms\Passport\DataProviders
 */
class TokenDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_key' => ['required', $this->app->make(ApplicationRule::class)],
            'app_secret' => ['required'],
            'token' => ['required',]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => trans('passport::app.auth.app_key'),
            'app_secret' => trans('passport::app.auth.app_secret'),
            'token' => trans('passport::app.auth.token'),
        ];
    }
}