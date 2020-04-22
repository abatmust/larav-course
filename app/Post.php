<?php

namespace App;

use App\Scopes\AdminShowDeletedScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Post extends Model
{
    use softDeletes;
    // protected $table = 'blogposts';

    protected $fillable = ['title', 'content', 'user_id'];

    public function comments()
    {
        return $this->hasMany('App\Comment')->dernier();
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function scopeMostCommented(Builder $query){
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }
    public static function boot(){
        static::addGlobalScope(new AdminShowDeletedScope);
        parent::boot();
       static::addGlobalScope(new LatestScope);
        static::deleting(function(Post $post){
            
            $post->comments()->delete();
        });
        static::restoring(function(Post $post){
            
            $post->comments()->restore();
        });
    }
}
