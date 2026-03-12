@extends('layouts.admin')

@section('title', 'Languages')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Language List</h4>

        <a href="{{ route('admin.languages.create') }}" class="btn btn-success">
            <i class="fa fa-plus"></i> Register New Language
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <style>
                .status-badge {
                    cursor: pointer;
                    font-size: 12px;
                    padding: 6px 10px;
                    border-radius: 999px;
                }
            </style>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="languages-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Language Code</th>
                            <th>Language Name</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>

</div>

<!-- Status Confirm Modal -->
<div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p id="statusConfirmText">Are you sure you want to change the status?</p>
                <input type="hidden" id="statusTargetId">
                <input type="hidden" id="statusTargetValue">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<!-- Edit Language Modal -->
<div class="modal fade" id="editLanguageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editLanguageForm">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div id="editSuccessMessage" 
                         class="alert alert-success d-none">
                        Language updated successfully!
                    </div>


                    <input type="hidden" id="edit_language_id">

                    <div class="mb-3">
                        <label class="form-label">Language Code</label>
                        <input type="text" 
                               id="edit_code" 
                               name="code" 
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Language Name</label>
                        <input type="text" 
                               id="edit_name" 
                               name="name" 
                               class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-secondary" 
                            data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" 
                            class="btn btn-primary">
                        Update Language
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


@endsection


@push('scripts')
<script>
$(document).ready(function() {

    $('#languages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.languages.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

});

$(document).on('click', '.editLanguageBtn', function () {

    let id = $(this).data('id');

    $.get('/admin/languages/' + id + '/edit', function (data) {

        $('#modalContent').html(data);
        $('#editModal').modal('show');

    });

});

//Edit Language

$(document).on('click', '.editLanguage', function () {
    let id = $(this).data('id');

    $.get('/admin/languages/' + id, function (response) {
            

        $('#edit_language_id').val(response.language.id);
        $('#edit_code').val(response.language.code);
        $('#edit_name').val(response.language.name);

        $('#editLanguageModal').modal('show');
                console.log('Edit clicked');

    });

});

//

$(document).on('submit', '#editLanguageForm', function (e) {

    e.preventDefault();

    let id = $('#edit_language_id').val();

    $.ajax({
        url: '/admin/languages/' + id,
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {

            $('#editLanguageModal').modal('hide');
            $('#languages-table').DataTable().ajax.reload(null, false);

        },
        error: function (xhr) {

            let errors = xhr.responseJSON.errors;

            $('.error-text').text('');

            if (errors) {
                $.each(errors, function (key, value) {
                    $('.' + key + '_error').text(value[0]);
                });
            }
        }
    });

});

// Status badge click
$(document).on('click', '.status-badge', function () {
    const id = $(this).data('id');
    const currentStatus = String($(this).data('status')).toLowerCase();
    const nextStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const label = nextStatus.charAt(0).toUpperCase() + nextStatus.slice(1);

    $('#statusTargetId').val(id);
    $('#statusTargetValue').val(nextStatus);
    $('#statusConfirmText').text('Are you sure you want to change the status to ' + label + '?');
    $('#statusConfirmModal').modal('show');
});

// Confirm status change
$('#confirmStatusBtn').on('click', function () {
    const id = $('#statusTargetId').val();
    const status = $('#statusTargetValue').val();

    $.ajax({
        url: '/admin/languages/' + id + '/status',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: status
        },
        success: function () {
            $('#statusConfirmModal').modal('hide');
            $('#languages-table').DataTable().ajax.reload(null, false);
        },
        error: function () {
            alert('Failed to update status');
        }
    });
});


</script>
@endpush
