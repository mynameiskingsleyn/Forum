@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center"> create a New thread</h2>
    <form action="{{ route('threads.store') }}" method="post">

        @include ('threads._partials._threadForm',[
          'submitButtonText' => 'Submit Thread',
          'thread'=> new Forum\Thread,
        ])


    </form>
    
</div>


@endsection
