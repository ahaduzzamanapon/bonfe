<form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" class="form-control">
    <button class="btn btn-primary mt-2">Import Users</button>
</form>
