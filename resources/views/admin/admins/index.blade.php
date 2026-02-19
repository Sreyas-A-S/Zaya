@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Doctors Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="iconly-Home icli svg-color"></i></a></li>
                        <li class="breadcrumb-item">Doctors</li>
                        <li class="breadcrumb-item active">List</li>
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
                        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                            <i class="iconly-Add-User icli me-2"></i>Register New Doctor
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="Admins-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone number</th>
                                        <th>Nationality</th>
                                        <th>Languages</th>
                                        <th>Status</th>
                                        <th>Actions</th>
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

<div class="modal fade" id="editLanguageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
        

    <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <table class="table table-bordered">

            <tr>
                <th width="200">Profile Picture</th>
                <td>
                    <input type="file" name="profile_picture" class="form-control">
                </td>
            </tr>

            <tr>
                <th>First Name</th>
                <td>
                    <input type="text" name="firstname" class="form-control" required>
                </td>
            </tr>

            <tr>
                <th>Last Name</th>
                <td>
                    <input type="text" name="lastname" class="form-control" required>
                </td>
            </tr>

            <tr>
                <th>Email</th>
                <td>
                    <input type="email" name="email" class="form-control" required>
                </td>
            </tr>

            <tr>
                <th>Country</th>
                <td>
                    <select name="country" class="form-control" required>
                        <option selected disabled>Select a Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <th>Language</th>
                <td>
                    <select name="language" class="form-control" required>
                        <option selected disabled>Select a Language</option>
                        @foreach ($languages as $language)
                            <option value="{{ $language->id }}">{{ $language->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <th>Password</th>
                <td>
                    <input type="password" name="password" class="form-control" required>
                </td>
            </tr>

            <tr>
                <th>Confirm Password</th>
                <td>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="text-center">
                    <button type="submit" class="btn btn-primary">
                        Create Admin
                    </button>
                </td>
            </tr>

        </table>

    </form>
       </div>
    </div>
</div>
    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#Admins-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.admins.index') }}",
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

            });
        </script>
        @endpush
    @endsection
