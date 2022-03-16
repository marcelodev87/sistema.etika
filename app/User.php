<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mail;
use App\Mail\ResetPassword as ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];


    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'dob', 'created_at', 'updated_at'
    ];

    public function sendPasswordResetNotification($token)
    {
        $data = [
            'link' => route('password.reset', ['token' => $token, 'email' => $this->email]),
            'name' => $this->name,
        ];
        return Mail::to($this->email)->send(new ResetPassword($data));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($role){
        if($this->roles()->where('slug', $role)->first()){
            return true;
        }
        return false;
    }

    public function hasAnyRole($roles)
    {
        if(is_array($roles)){
            foreach($roles as $role){
                if($this->hasRole($role)){
                    return true;
                }
            }
        }else{
            if($this->hasRole($roles)){
                return true;
            }
        }
        return false;
    }
}
