<?php

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