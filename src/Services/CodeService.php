<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-09-07 11:00
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services;

use CrCms\Foundation\App\Helpers\InstanceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Class CodeService
 * @package CrCms\Passport\Services
 */
class CodeService
{
    use InstanceTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * CodeService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function generate($key): string
    {
        $code = strval(mt_rand(100000, 999999));

        $this->cache->put($this->key($key), $code, Carbon::now()->addMinute(1));

        return $code;
    }

    /**
     * @param $key
     * @param string $code
     * @return bool
     */
    public function check($key, string $code): bool
    {
        if ($this->cache->has($key)) {
            return $code === $this->cache->get($key);
        }

        return false;
    }

    /**
     * @param $key
     * @return string
     */
    protected function key($key)
    {
        $prefix = $this->user ? $this->user->id : $this->request->ip();
        return $prefix . (is_array($key) ? implode('.', $key) : $key);
    }
}