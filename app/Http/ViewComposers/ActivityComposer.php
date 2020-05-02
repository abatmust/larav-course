<?php


namespace App\Http\ViewComposers;

use App\Post;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer {
    public function compose(View $view){

        $mostCommented = Cache::remember('mostCommented', now()->addSeconds(10), function(){
            return Post::withCount('comments')->with('user')->take(3)->get();
        });
        $mostActiveUsers = Cache::remember('mostActiveUsers', now()->addSeconds(10), function(){
            return User::mostActiveUsers()->take(5)->get();
        });
        $lastMonthMostActiveUsers = Cache::remember('lastMonthMostActiveUsers', now()->addSeconds(10), function(){
            return User::lastMonthMostActiveUsers()->take(5)->get();
        });

        $view->with([
            'mostCommented' => $mostCommented,
            'mostActiveUsers' =>  $mostActiveUsers ,
            'lastMonthMostActiveUsers' => $lastMonthMostActiveUsers,
        ]);
        
    }
}