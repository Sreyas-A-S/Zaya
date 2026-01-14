@extends('layouts.admin')

@section('title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 col-12">
                <h2>{{ $pageTitle }}</h2>
            </div>
            <div class="col-sm-6 col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-{{ isset($showFormInline) && $showFormInline ? '8' : '12' }}">
            <div class="card">
                <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                    <h3>{{ $pageTitle }} List</h3>
                    @if(!isset($showFormInline) || !$showFormInline)
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fa-solid fa-plus"></i> Create {{ $entityName }}
                    </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data will be populated via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($showFormInline) && $showFormInline)
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <h3>Create {{ $entityName }}</h3>
                </div>
                <div class="card-body">
                    <form id="createFormInline">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" id="name" type="text" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" type="email" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control" id="password" type="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" type="button" id="saveBtnInline">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create {{ $entityName }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control" id="name" type="text" name="name" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" type="email" name="email" placeholder="Enter email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" type="password" name="password" placeholder="Enter password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" id="saveBtn">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route($routePrefix . '.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            submitForm($('#createForm'), $(this), '#createModal');
        });

        $('#saveBtnInline').click(function(e) {
            e.preventDefault();
            submitForm($('#createFormInline'), $(this), null);
        });

        function submitForm(form, btn, modalId) {
            btn.html('Sending..');
            $.ajax({
                data: form.serialize(),
                url: "{{ route($routePrefix . '.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    form.trigger("reset");
                    if(modalId) $(modalId).modal('hide');
                    table.draw();
                    btn.html('Save');
                },
                error: function(data) {
                    console.log('Error:', data);
                    btn.html('Save');
                }
            });
        }
        
        // Handle Delete
        $('body').on('click', '.deleteUser', function () {
            var user_id = $(this).data("id");
            if(confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('admin/' . $urlSegment) }}" + '/' + user_id,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
</script>
@endsection