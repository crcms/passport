<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/5 21:43
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Models;

use CrCms\Foundation\App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBehaviorModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_behaviors';

    /**
     * @var array
     */
    protected $casts = [
        'extension' => 'object'
    ];

    /**
     * @return BelongsTo
     */
    public function belongsToUser(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
}