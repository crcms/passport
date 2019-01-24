<?php

namespace CrCms\Passport\Tasks;

use CrCms\Foundation\Tasks\AbstractTask;
use CrCms\Passport\Attributes\ApplicationAttribute;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\DomainModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Repositories\DomainRepository;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Support\Collection;

/**
 * Class UserApplicationTask
 * @package CrCms\Passport\Tasks
 */
final class UserApplicationTask extends AbstractTask
{
    /**
     * @param mixed ...$params
     * @return Collection
     */
    public function handle(...$params): Collection
    {
        /* @var UserModel $user */
        $user = $params[0];

        return $this->appendApplications($this->selfApplications($user));
    }

    /**
     * @param UserModel $user
     * @return Collection
     */
    protected function selfApplications(UserModel $user): Collection
    {
        /* @var UserRepository $userRepository */
        $userRepository = $this->app->make(UserRepository::class);
        return $userRepository->applicationsByUser($user);
    }

    /**
     * @param Collection $applications
     * @return Collection
     */
    protected function appendApplications(Collection $applications): Collection
    {
        /* @var ApplicationRepository $applicationRepository */
        $applicationRepository = $this->app->make(ApplicationRepository::class);
        /* @var DomainRepository $domainRepository */
        $domainRepository = $this->app->make(DomainRepository::class);

        return $applications->filter(function (ApplicationModel $application) {
            return $application->status === ApplicationAttribute::STATUS_NORMAL;
        })->map(function (ApplicationModel $application) use ($applicationRepository, $domainRepository) {
            return $applicationRepository->domainsByApplication($application)->map(function (DomainModel $domain) use ($domainRepository) {
                return $domainRepository->applicationsByDomain($domain);
            })->flatten(1);
        })->flatten(1);
    }
}