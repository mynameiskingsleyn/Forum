{{csrf_field()}}
<div class="form-group">
    <label for="body">{{ $labelTitle ?? 'Body'}}</label>
    <textarea name="description" id="description" class="form-control">{{ old('body') ?? $reply->body }}</textarea>
</div>
<input type="submit" class="btn btn-success" value="{{ $submitButtonText ?? 'Submit Question' }}">
