<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:38
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Requests\Auth;

use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class TokenRequest
 * @package CrCms\Passport\Http\Api\Requests\Auth
 */
class TokenRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_key' => ['required', Rule::exists((new ApplicationModel())->getTable(), 'app_key')],
            'token' => ['required',]
        ];
    }

    /**
     * @return array
     */
    protected function validationData(): array
    {
        return array_merge($this->all(), ['token' => $this->cookie('token')]);
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => trans('passport::app.auth.app_key'),
            'token' => trans('passport::app.auth.token'),
        ];
    }
}