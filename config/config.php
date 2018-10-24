<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 1 hour.
    |
    | You can also set this to null, to yield a never expiring token.
    | Some people may want this behaviour for e.g. a mobile app.
    | This is not particularly recommended, so make sure you have appropriate
    | systems in place to revoke the token if necessary.
    |
    */

    'ttl' => env('PASSPORT_TTL', 100),

    /*
    |--------------------------------------------------------------------------
    | Refresh time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token can be refreshed
    | within. I.E. The user can refresh their token within a 2 week window of
    | the original token being created until they must re-authenticate.
    | Defaults to 2 weeks.
    |
    | You can also set this to null, to yield an infinite refresh time.
    | Some may want this instead of never expiring tokens for e.g. a mobile app.
    | This is not particularly recommended, so make sure you have appropriate
    | systems in place to revoke the token if necessary.
    |
    */

    'refresh_ttl' => env('PASSPORT_REFRESH_TTL', 20160),

    /*
    |--------------------------------------------------------------------------
    | Fields allowed to be registered
    |--------------------------------------------------------------------------
    |
    | Set validation rules for fields that are allowed to be registered
    | Field range: [name,mobile,email]
    | Where name, email, mobile must be unique
    |
    */

    'register_rule' => [
        'name' => 'required|string|max:15|unique:passport_users',
        'mobile' => 'required|string|max:11|min:11|unique:passport_users',
        'email' => 'required|string|email|max:50|unique:passport_users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fields allowed to be login
    |--------------------------------------------------------------------------
    |
    | Set validation rules for fields that are allowed to be login
    | Field range: [name,mobile,email,'password']
    |
    */

    'login_rule' => [
        'name' => ['required'],
        'password' => ['required'],
    ],
];