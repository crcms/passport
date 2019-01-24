<?php

namespace CrCms\Passport\Models;

use CrCms\Foundation\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class ApplicationModel
 * @package CrCms\Passport\Models
 */
class ApplicationModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'applications';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string
     */
    protected $primaryKey = 'app_key';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return BelongsToMany
     */
    public function belongsToManyDomain(): BelongsToMany
    {
        return $this->belongsToMany(DomainModel::class, 'applications_domains', 'app_key', 'domain_id');
    }
}