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
    protected $table = 'passport_applications';

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
        return $this->belongsToMany(DomainModel::class, 'passport_applications_domains', 'app_key', 'domain_id');
    }

    /**
     * belongsToManyUser
     *
     * @return BelongsToMany
     */
    public function belongsToManyUser(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'passport_user_applications', 'app_key', 'user_id');
    }
}
