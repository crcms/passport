<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-30 07:40
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Commands;

use Carbon\Carbon;
use CrCms\Passport\Attributes\ApplicationAttribute;
use CrCms\Passport\Repositories\ApplicationRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class CreateApplicationCommand
 * @package CrCms\Modules\passport\src\Commands
 */
class CreateApplicationCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'make:application';

    /**
     * @var string
     */
    protected $description = 'Create a application';

    /**
     * @var ApplicationRepository
     */
    protected $repository;

    /**
     * CreateApplicationCommand constructor.
     * @param ApplicationRepository $repository
     */
    public function __construct(ApplicationRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     *
     */
    public function handle()
    {
        $this->repository->create([
            'app_key' => Carbon::now()->getTimestamp(),
            'app_secret' => sha1(uniqid()),
            'status' => ApplicationAttribute::STATUS_NORMAL,
        ]);

        $this->info("Successfully created the application");
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The application name.']
        ];
    }
}