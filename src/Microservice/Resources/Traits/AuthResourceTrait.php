<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-30 19:00
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Microservice\Resources\Traits;

use CrCms\Microservice\Server\Contracts\RequestContract;

/**
 * Trait AuthResourceTrait
 * @package CrCms\Passport\Http\Api\Resources\Traits
 */
trait AuthResourceTrait
{
    /**
     * @param RequestContract
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'jwt' => $this->resource['jwt'],
            'cookie' => $this->resource['cookie'],
        ];
    }
}