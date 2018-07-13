<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class LogoutAction
 * @package CrCms\Passport\Actions
 */
class LogoutAction implements ActionContract
{
    /**
     * @param Collection|null $collects
     * @return void
     */
    public function handle(?Collection $collects = null)
    {
        Auth::guard()->logout();
    }
}