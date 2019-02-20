<?php

namespace CrCms\Passport\DataProviders\User;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Validation\Rule;

class ShowDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_key' => ['required', Rule::exists((new ApplicationModel)->getTable(), 'app_key')],
            'id' => ['required', 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => '应用ID',
            'id' => '用户ID',
        ];
    }
}
