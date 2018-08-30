<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-30 19:00
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Resources\Traits;

use function CrCms\Foundation\App\Helpers\combination_url;

/**
 * Trait AuthResourceTrait
 * @package CrCms\Passport\Http\Api\Resources\Traits
 */
trait AuthResourceTrait
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'jwt' => $this->resource['jwt'],
            'cookie' => $this->resource['cookie'],
            $this->mergeWhen($request->input('_redirect'), function () use ($request) {
                return ['url' => combination_url($request->input('_redirect'), $this->resource['jwt'])];
            }),
        ];
    }
}