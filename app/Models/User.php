<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function resources()
    {
        return $this->hasMany('App\Resource');
    }

    public function githubUser()
    {
        return $this->hasOne('App\GithubUser', 'id', 'github_id');
    }

    public function getStars()
    {
        return $this->hasMany('App\Star');
    }

    public function badges()
    {
        return $this->hasMany('App\Badge')/*->orderBy('name', 'asc')*/;
    }
}
