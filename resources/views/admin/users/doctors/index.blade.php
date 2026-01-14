@extends('layouts.admin')

@section('title', 'Doctors')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 col-12">
                <h2>Doctors</h2>
            </div>
            <div class="col-sm-6 col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Doctors</li>
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
                    <h3>Doctors List</h3>
                    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> Create Doctor
                    </a>
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
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.doctors.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        
        // Handle Delete
        $('body').on('click', '.deleteUser', function () {
            var user_id = $(this).data("id");
            if(confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('admin/doctors') }}" + '/' + user_id,
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