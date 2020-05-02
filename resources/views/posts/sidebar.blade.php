<x-card title="MOST COMMENTED POSTS">
    <ul class="list-group list-group-flush">
        @foreach ($mostCommented as $post)
            <li class="list-group-item">
                <a href="">{{$post->title}}</a>
                <p> <span class="badge badge-success">{{ $post->comments_count }}</span> comments</p>
            </li>
        @endforeach
        
    </ul>
</x-card>
<x-card 
    title="MOST ACTIVE USERS" 
    text="Most active users" 
    :items="collect($mostActiveUsers)->pluck('name')">
</x-card>
<x-card 
    title="LAST MONTH MOST ACTIVE USERS" 
    text="last month most active users" 
    :items="collect($mostActiveUsers)->pluck('name')">
</x-card>