
<div class="form-group">
    <label for="body">Details</label>
    <textarea name="body" id="body" class="form-control">{{ old('body') ?? $reply->body }}</textarea>
</div>
<input type="submit" class="btn btn-success" value="{{ $submitButtonText ?? 'Submit Reply' }}">
