<?php

namespace App;

use App\Scopes\AdminShowDeletedScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Support\Facades\Cache;



class Post extends Model
{
    use softDeletes;
    // protected $table = 'blogposts';

    protected $fillable = ['title', 'content', 'user_id'];

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable')->dernier();
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeMostCommented(Builder $query){
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }
    public function scopeWithCommentsCtUserTags(Builder $query){
        return $query->withCount('comments')->with(['user', 'tags']);
    }

    public function image(){
        return $this->morphOne('App\image', 'imageable');
    }

    public static function boot(){
        static::addGlobalScope(new AdminShowDeletedScope);
        parent::boot();
       static::addGlobalScope(new LatestScope);
        static::deleting(function(Post $post){
            
            $post->comments()->delete();
        });
        static::updating(function(Post $post){
            Cache::forget("post-show-{$post->id}");
        
            });
        static::restoring(function(Post $post){
            
            $post->comments()->restore();
        });
    }
    public function tags(){
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
