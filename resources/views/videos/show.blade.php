@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>A Premium Videos</h1>
        @include('videos.'.$video->permissionStatusFor(auth()->user()))
    </div>
@endsection