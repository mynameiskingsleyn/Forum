@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
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

                        @include('threads._partials._list')


                        {{ $threads->render() }}

                    </div>
                </div>
            </div>
            <div class="col-md-4">
              @if(count($trending))
              <div class="panel panel-default">
                <div class="panel-heading">
                   Trending Threads
                </div>
                <div class="panel-body">
                  <ul class="list-group">
                    @foreach($trending as $thread)
                      <li class="list-group-item">
                        <a href="{{ url($thread->path)}}">
                          {{ $thread->title }}
                        </a>

                      </li>
                    @endforeach
                  </ul>


                </div>
              </div>
              @endif
            </div>
        </div>
    </div>

@endsection
