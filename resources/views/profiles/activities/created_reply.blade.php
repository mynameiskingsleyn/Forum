@component('profiles.activities.activity')
  @slot('heading')
    {{$profile_user->name }} replied to <h4><a href="{{$activity->subject->thread->path()}}">{{ $activity->subject->thread->title }}</a></h4>
  @endslot

  @slot('body')

  {{ $activity->subject->body }}
  @endslot

@endcomponent
