@extends('layouts.admin')

@section('title', 'Manage ' . $title)

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Manage {{ $title }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                    <h3>{{ $title }} List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="fa-solid fa-plus me-2"></i>Add New Item
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="master-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Modal -->
<div class="modal fade" id="item-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add New Item</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="item-form">
                @csrf
                <input type="hidden" name="id" id="item_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control" id="name" name="name" type="text" required placeholder="Enter name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="save-btn">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let table;
    $(document).ready(function() {
        table = $('#master-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.master-data.index', $type) }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#item-form').on('submit', function(e) {
            e.preventDefault();
            const id = $('#item_id').val();
            const url = id ? "{{ url('admin/master-data') }}/{{ $type }}/" + id : "{{ route('admin.master-data.store', $type) }}";
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize() + (id ? '&_method=PUT' : ''),
                success: function(response) {
                    $('#item-modal').modal('hide');
                    table.draw();
                    alert(response.success);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        $('body').on('click', '.editItem', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const status = $(this).data('status');
            
            $('#modal-title').text('Edit Item');
            $('#item_id').val(id);
            $('#name').val(name);
            $('#status').val(status);
            $('#item-modal').modal('show');
        });

        $('body').on('click', '.deleteItem', function() {
            if (confirm("Are you sure you want to delete this item?")) {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/master-data') }}/{{ $type }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        table.draw();
                        alert(response.success);
                    }
                });
            }
        });
    });

    function openCreateModal() {
        $('#item-form')[0].reset();
        $('#item_id').val('');
        $('#modal-title').text('Add New Item');
        $('#item-modal').modal('show');
    }
</script>
@endsection
