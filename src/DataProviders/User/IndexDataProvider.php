<?php

namespace CrCms\Passport\DataProviders\User;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Validation\Rule;

/**
 * Class IndexDataProvider
 * @package CrCms\Passport\DataProviders\User
 */
class IndexDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_key' => [Rule::exists((new ApplicationModel)->getTable(), 'app_key')],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => '应用ID',
            'name' => '用户名',
            'mobile' => '手机号',
            'password' => '密码',
            'status' => '状态'
        ];
    }
}