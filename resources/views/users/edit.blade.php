@extends('layouts.app')

@section('content')
<form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
    
    @csrf

    @method('PUT')

    <div class="row">
        <div class="col-md-4">
            <h5>User Avatar</h5>
        <img src="{{ $user->image ? $user->image->url(): ''}}" alt="" class="img-thumbnail">
            <input type="file" name="avatar" id="avatar" class="form-control-file">
        </div>
        <div class="col-md-8">
            <div class="form-froup">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <button class="btn btn-warning btn-block">Update</button>
        </div>
    </div>
</form>
@endsection