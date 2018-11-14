<?php

namespace CrCms\Passport\Repositories;

use CrCms\Repository\AbstractRepository;
use CrCms\Passport\Models\DomainModel;

/**
 * Class DomainRepository
 * @package CrCms\Passport\Repositories
 */
class DomainRepository extends AbstractRepository
{
    /**
     * @return DomainModel
     */
    public function newModel(): DomainModel
    {
        return new DomainModel;
    }
}