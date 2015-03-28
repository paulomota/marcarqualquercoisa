@extends('app')

@section('content')
    Page of Users!

    <div
	  class="fb-like"
	  data-share="true"
	  data-width="450"
	  data-show-faces="true">
	</div>

    @foreach($users as $user)
        <p>{{ $user->name }}</p>
    @endforeach
@stop