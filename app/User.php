<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
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
    public function posts(){
        return $this->hasMany(Post::class);
    }
    public function scopeMostActiveUsers(Builder $query){
        return $query->withCount('posts')->orderBy('posts_count', 'desc');
    }

    public function scopeLastMonthMostActiveUsers(Builder $query){
        return $query->withCount(['posts'=> function(Builder $query){
            $query->whereBetween(static::CREATED_AT, [now()->subMonth(1), now()]);
        }])
        ->having('posts_count','>', 34)
        ->orderBy('posts_count', 'desc');
    }
}
