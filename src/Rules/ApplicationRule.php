<?php

namespace CrCms\Passport\Rules;

use CrCms\Foundation\Helpers\InstanceConcern;
use CrCms\Foundation\Transporters\AbstractDataProvider;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Attributes\ApplicationAttribute;
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class ApplicationRule
 * @package CrCms\Passport\Rules
 */
class ApplicationRule implements Rule
{
    use InstanceConcern;

    /**
     * @var ApplicationRepository
     */
    protected $repository;

    /**
     * @var AbstractDataProvider
     */
    protected $data;

    /**
     * ApplicationRule constructor.
     * @param ApplicationRepository $repository
     */
    public function __construct(ApplicationRepository $repository, DataProviderContract $dataProvider)
    {
        $this->repository = $repository;
        $this->data = $dataProvider;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            $application = $this->repository->cache(10)->byAppKeyOrFail($value);
        } catch (ResourceNotFoundException $exception) {
            return false;
        }

        return $application->app_secret === $this->data->get('app_secret') && $application->status === ApplicationAttribute::STATUS_NORMAL;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The application not found';
    }
}