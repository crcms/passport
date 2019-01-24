<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-04 21:02
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Models;

use CrCms\Foundation\Models\Model;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\ForgetPasswordEvent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class UserModel
 * @package CrCms\Passport\Models
 */
class UserModel extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * @var string
     */
    protected $table = 'passport_users';

    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'ticket_expired_at'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $JWTCustomClaims = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return $this->JWTCustomClaims;
    }

    /**
     * @param array $claims
     * @return $this
     */
    public function setJWTCustomClaims(array $claims)
    {
        $this->JWTCustomClaims = $claims;
        return $this;
    }

    /**
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        event(new ForgetPasswordEvent(
            $this,
            UserAttribute::AUTH_TYPE_FORGET_PASSWORD,
            $token,
            ['ip' => app('request')->ip(), 'agent' => app('request')->userAgent()]
        ));
        //$this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return BelongsToMany
     */
    public function belongsToManyApplication(): BelongsToMany
    {
        return $this->belongsToMany(ApplicationModel::class, 'user_applications', 'user_id', 'app_key');
    }
}