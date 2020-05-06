<?php

namespace App;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = ['content', 'user_id'];

    // blog_post_id
    public function post()
    {
        // return $this->belongsTo('App\BlogPost', 'post_id', 'blog_post_id');
        return $this->belongsTo('App\Post');

    }
    public function user()
    {
        // return $this->belongsTo('App\BlogPost', 'post_id', 'blog_post_id');
        return $this->belongsTo('App\User');

    }

    public function commentable(){

        return $this->morphTo();
    }

    public function scopeDernier(Builder $query){
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
    public static function boot(){
        parent::boot();
       // static::addGlobalScope(new LatestScope);

       static::creating(function(Comment $comment){

            Cache::forget("post-show-{$comment->commentable->id}");
    
        });
        
        
    }


    
}
