<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-30 07:40
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Modules\passport\src\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class CreateApplicationCommand
 * @package CrCms\Modules\passport\src\Commands
 */
class CreateApplicationCommand extends Command
{
    protected $name = 'make:application';

    /**
     * @var string
     */
    protected $description = 'Create a application';

    
    public function handle()
    {

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