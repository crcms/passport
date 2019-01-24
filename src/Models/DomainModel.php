<?php

namespace CrCms\Passport\Models;

use CrCms\Foundation\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class DomainModel
 * @package CrCms\Passport\Models
 */
class DomainModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'passport_domains';

    /**
     * @return BelongsToMany
     */
    public function belongsToManyApplication(): BelongsToMany
    {
        return $this->belongsToMany(ApplicationModel::class, 'passport_applications_domains', 'domain_id', 'app_key');
    }
}