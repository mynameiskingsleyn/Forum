<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply_{{ $reply->id }}"class="panel panel-default">
        <div class="panel-heading" style="padding-bottom:20px;">
          <div class="level">
            <div class="flex">
              Created: {{ $reply->created_at->diffForHumans() }}
              By: <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }} </a>
              <span class="likes"> </span>
            </div>
            <span class="level">

              @can('update',$reply)
                <div class="">
                  <span style="float:right; margin-bottom:20px;">

                    <!-- <form class="" action="{{route('reply.delete',$reply)}}" method="post">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <button type="submit" name="button" class="btn btn-danger btn-xs">Delete Reply</button>
                    </form> -->
                    <button type="button" name="button" class="btn btn-xs btn-danger" @click="destroy">Delete Reply</button>
                    <button type="button" name="button" class="btn btn-xs" @click="editing=true">Edit Reply</button>
                    <!-- <a href="/reply/{{$reply->id}}/edit/" class="btn btn-xs">Edit Reply</a> -->
                  </span>

                </div>

              @endcan

            </span>

          </div>


        </div>
        <div class="panel-body">

          <div class="level">
            <div class="flex" v-if="editing">
              <div class="form-group">
                <textarea name="name" rows="8" class="form-control" v-model="body">

                </textarea>
              </div>
              <button type="button" name="button" class="btn btn-xs btn-link" @click="update"> Update </button>
              <button type="button" name="button" class="btn btn-xs btn-link" @click="editing=false"> Cancel </button>
            </div>
            <div class="flex" v-else v-text="body">

            </div>
            @if(Auth::check())
            <div class="">
              <!-- {{ $reply->getFavoritesCountAttribute() }} {{ str_plural('favorite',$reply->getFavoritesCountAttribute()) }} -->
              <favorite :reply="{{ $reply }}"> </favorite>

              <!-- <form class="" action="/replies/{{$reply->id}}/favorites" method="post">
                {{ csrf_field() }}
                  <button type="submit" class="btn btn-default" {{ $reply->isFavorited()? 'disabled': ''}}>
                    Favorite
                  </button>
              </form> -->
            </div>
            @endif

          </div>

            <br>

        </div>
    </div>
</reply>
