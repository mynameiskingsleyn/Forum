<article>
  <div class="level">
   <a href="{{ $thread->path() }}" class="flex"> <h4 >{{ $thread->title }}</h4> </a>
   <strong>{{ $thread->replies_count }} {{str_plural('reply',$thread->replies_count)}}</strong>
 </div>
    <div class="body">{{ $thread->body }}</div>
    <a href="{{ $thread->path() }}" class="btn btn-primary">View Thread</a>

</article>
    <hr>
