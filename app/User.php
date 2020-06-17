<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password','tel','chat_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function fences()
    {
        return $this->hasMany('App\Fence', 'user_id', 'id');
    }


    public function routeNotificationForTelegramaaaaaaaaaaaaa()
    {
        #dd(__FUNCTION__);
        return '-1001490952251';

        #return '1221455806';
        #return '262757621';
        #return '@Uniriofencebot';
    }

    public function routeNotificationForMail($notification)
    {


        // Return name and email address...
        return [$this->email => $this->name];
    }

}
