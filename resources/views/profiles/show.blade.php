@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="page-header">
      <h1>
          {{ $profile_user->name }}
          <small>member since {{ $profile_user->created_at->diffForHumans() }}</small>
      </h1>
    </div>
    <h2>Activities</h2>
    @forelse($activities as $date => $activity)
      <h2>{{$date}}</h2>
      @foreach($activity as $record)
        @if(view()->exists("profiles.activities.{$record->type}"))
          @include ("profiles.activities.{$record->type}",['activity' => $record])
        @endif
      @endforeach
    @empty
      <h3> This user has no activities at this moment..</h3>
    @endforelse

  </div>





@endsection
