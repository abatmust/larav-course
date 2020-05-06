@auth
<h4>Add a comment</h4>
    <form method="POST" action="{{ $action }}">
        @csrf
        
        <textarea class="form-control my-3" name="content" id="content" rows="3"></textarea>
        <x-errors my-class="warning"></x-errors>
        <button type="submit" class="btn btn-primary btn-block">Create!</button>
    </form>
   
@else
    <a href="/login" class="btn btn-success btn-sm">Sign In</a> to post a comment!
@endauth