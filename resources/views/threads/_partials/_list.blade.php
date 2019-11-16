@forelse($threads as $thread)
    <article>
      <div class="level">
        <div class="flex">
          <a href="{{ $thread->path() }}">
             @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
               <strong> <h3> {{ $thread->title }}</h3>  </strong>
              @else
               <h4>
                 {{ $thread->title }}
               </h4>
               @endif
           </a>
             <h5>Posted By: <a href="{{ route('profile.show',$thread->owner)}}">{{ $thread->owner->name }} </a></h5>&nbsp; &nbsp;
        </div>
        <span>
          Has ->
          <strong>{{ $thread->replies_count }} {{str_plural('reply',$thread->replies_count)}}</strong>
        </span>

     </div>
      <div class="panel">
        <div class="panel-body">
          <div class="body">{{ $thread->body }}</div>
          <a href="{{ $thread->path() }}" class="btn btn-primary">View Thread</a>
        </div>
        <div class="panel-footer">
          Has {{ $thread->visits }} visits and {{ $thread->rank }}
        </div>
      </div>


    </article>
        <hr>
@empty
  <p>There are no Threads associated with this channel at this time</p>
@endforelse
