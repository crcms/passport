<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-09-07 10:32
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Commands;

use CrCms\Passport\Attributes\ApplicationAttribute;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Repositories\ApplicationRepository;
use Illuminate\Console\Command;

/**
 * Class ApplicationListCommand
 * @package CrCms\Modules\passport\src\Commands
 */
class ApplicationListCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'passport:list';

    /**
     * @param ApplicationRepository $repository
     */
    public function handle(ApplicationRepository $repository)
    {
        $applications = $repository->all()->map(function (ApplicationModel $model) {
            return [
                'id' => $model->id,
                'name' => $model->app_key,
                'secret' => $model->app_secret,
                'status' => ApplicationAttribute::getStaticTransform('status.' . strval($model->status)),
                'datetime' => $model->created_at->toDateTimeString(),
            ];
        });

        $this->table(['id', 'name', 'secret', 'status', 'datetime'], $applications);
    }
}