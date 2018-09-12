{{csrf_field()}}
<div class="form-group">
  <label for="channel_id">Choose a Channel:</label>
  <select name="channel_id" class="form-control" required>
    <option value="">Choose One..</option>
    @foreach($channels as $channel)
      <option value="{{$channel->id}}" {{ old('channel_id') == $channel->id ? 'selected': ''}}>{{$channel->name}}</option>
    @endforeach
  </select>
</div>
<div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title') ?? $thread->title }}" required minlength=
    "4" >
</div>
<div class="form-group">
    <label for="body">{{ $labelTitle ?? 'Body'}}</label>
    <textarea name="body" id="body" class="form-control" required minlength="10">{{ old('body') ?? $thread->body }}</textarea>
</div>

<input type="submit" class="btn btn-success" value="{{ $submitButtonText ?? 'Submit Thread' }}">
