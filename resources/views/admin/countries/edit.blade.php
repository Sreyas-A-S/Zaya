@extends('admin.layouts.app')

@section('content')

<!-- Edit Country Modal -->
<div class="modal fade" id="editCountryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">     
      <div class="modal-header">
        <h5 class="modal-title">Edit Country</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="editCountryForm">
          <input type="hidden" id="countryId">

          <div class="mb-3">
            <label>Name</label>
            <input type="text" id="countryName" class="form-control">
          </div>

          <div class="mb-3">
            <label>Code</label>
            <input type="text" id="countryCode" class="form-control">
          </div>

          <button type="submit" class="btn btn-primary">
            Save Changes
          </button>
        </form>
      </div>
    </div>
  </div>
</div>



@endsection
