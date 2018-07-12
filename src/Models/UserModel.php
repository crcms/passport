<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-04 21:02
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Models;

use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\ForgetPasswordEvent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class UserModel
 * @package CrCms\Passport\Models
 */
class UserModel extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable;

    /**
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $guarded = [];

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
        return [];
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
}