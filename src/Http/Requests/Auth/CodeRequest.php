<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-09-07 11:00
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CodeRequest
 * @package CrCms\Passport\Http\Requests\Auth
 */
class CodeRequest extends FormRequest
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
            'object' => ['required'],
            'type' => ['required', 'integer'],
            'app_key' => ['required'],
        ];
    }
}