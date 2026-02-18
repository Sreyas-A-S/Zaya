<form id="editCountryForm" data-id="{{ $country->id }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Code</label>
        <input type="text" name="code" value="{{ $country->code }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" value="{{ $country->name }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Flag</label>
        <input type="text" name="flag" value="{{ $country->flag }}" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Update</button>
</form>
