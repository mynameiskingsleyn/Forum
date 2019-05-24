@extends('layouts.app')
@section('header')
  <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
  <script type="text/javascript">
     window.thread = <?= json_encode($thread) ?>
  </script>
@endsection
@section('content')
<thread-view :initial-Replies-Count ="{{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Forum <span style="float:right;">{{$ch->name}}</span></div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <img src="{{  $thread->owner->avatar_path }}" alt="{{ $thread->owner->name }}" width="40" class="mr-1">
                            <article>
                                <h4>{{ $thread->title }} <br>By: <a href="/profiles/{{ $thread->owner->name }}">{{ $thread->owner->name }} </a></h4>
                                @can('update', $thread)
                                  <div class="" style="float:right;">

                                    <form class="" action="{{ route('threads.destroy',$thread->id)}}" method="post">
                                      {{csrf_field()}}
                                      {{method_field('DELETE')}}
                                        <button type="submit" name="button" class="btn btn-link">Delete Thread</button>
                                    </form>

                                  </div>
                                @endcan
                                <div class="body">{{ $thread->body }}</div>

                            </article>


                    </div>

                </div>
                <div class="">
                  <replies :data="{{ $replies }}" :thread="{{ $thread }}" @removed="repliesCount--"
                  @added="repliesCount++"> </replies>
                </div>


                 @if(auth()->check())

                    <!-- @include('threads._partials.forms._replyForm') -->
                @else
                  <p class="text-center">Please <a href="{{ route('login') }}">Sign in </a> to participate in the discussion</p>
                @endif
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                  <div class="panel-body">
                      <article>
                          <p>This thread was published {{ $thread->created_at->diffForHumans() }}
                            by <a href="/profiles/{{ $thread->owner->name }}">{{ $thread->owner->name }}</a> with <span v-text="repliesCount">  </span>
                            {{str_plural('reply',$thread->replies_count)}}
                            and {{ $thread->owner->name }} has currently published a total of {{ $thread->owner->threads_count }}
                            {{ str_plural('Thread',$thread->owner->threads_count) }}
                          </p>
                          <p class="text-center"><a href="/threads?by={{$thread->owner->name}}"> More Threads </a> by {{$thread->owner->name}}</p>
                          <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                          </p>
                      </article>
                      <hr>
                  </div>
              </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection
