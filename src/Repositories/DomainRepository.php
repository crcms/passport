<?php

namespace CrCms\Passport\Repositories;

use CrCms\Repository\AbstractRepository;
use CrCms\Passport\Models\DomainModel;
use Illuminate\Support\Collection;

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

    /**
     * @param DomainModel $domain
     * @return Collection
     */
    public function applicationsByDomain(DomainModel $domain): Collection
    {
        return $domain->belongsToManyApplication()->get();
    }
}