<h3>Add a reply</h3>
<form action="{{ $thread->repPostPath() }}" method="Post">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="body">Details</label>
        <textarea name="body" id="body" class="form-control"></textarea>
    </div>
    <input type="submit" class="btn btn-success" value="Submit Reply">

</form>
