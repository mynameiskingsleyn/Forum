@component('profiles.activities.activity')
  @slot('heading')
    <a href="{{ $activity->subject->favorited->path() }}">
      {{$profile_user->name }} Favored  a reply {{ $activity->subject->favorited->owner->name }}
    </a>

  @endslot

  @slot('body')

    {{ $activity->subject->favorited->body }}
  @endslot

@endcomponent
