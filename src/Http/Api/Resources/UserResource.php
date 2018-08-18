<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-18 15:14
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Resources;

use CrCms\Foundation\App\Http\Resources\Resource;

/**
 * Class UserResource
 * @package CrCms\Passport\Http\Api\Resources
 */
class UserResource extends Resource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
        ];
    }
}