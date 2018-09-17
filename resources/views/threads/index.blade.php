@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Forum<span style="float:right;">
                      @if($channel->name)
                        {{$channel->name}}
                      @else
                        All Threads
                      @endif
                  </span></div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @forelse($threads as $thread)
                            <article>
                              <div class="level">
                               <a href="{{ $thread->path() }}" class="flex"> <h4 >{{ $thread->title }}</h4> </a>
                               <strong>{{ $thread->replies_count }} {{str_plural('reply',$thread->replies_count)}}</strong>
                             </div>
                                <div class="body">{{ $thread->body }}</div>
                                <a href="{{ $thread->path() }}" class="btn btn-primary">View Thread</a>

                            </article>
                                <hr>
                        @empty
                          <p>There are no Threads associated with this channel at this time</p>
                        @endforelse

                        {{ $threads->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
