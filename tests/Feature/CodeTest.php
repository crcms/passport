<?php

/**
 * @author simon <434730525@qq.com>
 * @datetime 2018-09-07 13:40
 * @link http://www.koodpower.com/
 * @copyright Copyright &copy; 2018 Rights Reserved 快点动力
 */

namespace CrCms\Passport\Tests\Feature;

use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Tests\TestCase;

/**
 * Class CodeTest
 * @package CrCms\Passport\Tests\Feature
 */
class CodeTest extends TestCase
{
    /**
     *
     */
    public function testGetCode()
    {
        $response = $this->post(route('passport.code.post'),[
            'object' => 'rewrew@zz.com',
            'app_key' => '1111111111',
            'type' => UserAttribute::AUTH_TYPE_FORGET_PASSWORD,
        ]);

        $this->assertEquals($response->getStatusCode(),204);
    }
}