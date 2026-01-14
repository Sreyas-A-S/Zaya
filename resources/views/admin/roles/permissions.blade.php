@extends('layouts.admin')

@section('title', 'Assign Permissions - ' . $role->name)

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Assign Permissions</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active">Assign Permissions</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Role: <span class="text-primary">{{ $role->name }}</span></h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" id="select-all-btn"><i class="fa-solid fa-check-double me-2"></i>Select All</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-dark"><i class="fa-solid fa-arrow-left me-2"></i> Back to Roles</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 25%;">Module / Group</th>
                                    <th class="text-center">View</th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Edit</th>
                                    <th class="text-center">Delete</th>
                                    <th class="text-center">Other</th>
                                    <th class="text-center" style="width: 80px;">All</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $standardActions = ['view', 'create', 'edit', 'delete'];
                                @endphp
                                @foreach($permissions as $group => $groupPermissions)
                                <tr>
                                    <td class="f-w-600">{{ $group }}</td>
                                    @php
                                    $renderedIds = [];
                                    @endphp
                                    @foreach($standardActions as $action)
                                    <td class="text-center">
                                        @php
                                        // Simple matching: check if slug contains the action keyword
                                        $perm = $groupPermissions->first(function($p) use ($action) {
                                        $s = strtolower($p->slug);
                                        $a = strtolower($action);
                                        return str_contains($s, $a);
                                        });
                                        @endphp
                                        @if($perm)
                                        @php $renderedIds[] = $perm->id; @endphp
                                        <div class="form-check checkbox-primary justify-content-center mb-0">
                                            <input type="checkbox"
                                                class="form-check-input permission-checkbox"
                                                value="{{ $perm->id }}"
                                                id="perm_{{ $perm->id }}"
                                                {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $perm->id }}"></label>
                                        </div>
                                        @else
                                        <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    @endforeach

                                    {{-- Other Permissions in same group that don't fit standard actions --}}
                                    <td class="text-center">
                                        @php
                                        $others = $groupPermissions->whereNotIn('id', $renderedIds);
                                        @endphp
                                        @if($others->count() > 0)
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            @foreach($others as $other)
                                            <div class="form-check checkbox-primary d-flex align-items-center gap-2 mb-0">
                                                <input type="checkbox"
                                                    class="form-check-input permission-checkbox"
                                                    value="{{ $other->id }}"
                                                    id="perm_{{ $other->id }}"
                                                    {{ $role->permissions->contains($other->id) ? 'checked' : '' }}>
                                                <label class="form-check-label small mb-0" for="perm_{{ $other->id }}">{{ str_replace($group, '', $other->name) }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center bg-light-primary">
                                        <div class="form-check checkbox-primary justify-content-center mb-0">
                                            <input type="checkbox" class="form-check-input group-row-select" id="row_select_{{ $loop->index }}" title="Select All in Group">
                                            <label class="form-check-label" for="row_select_{{ $loop->index }}"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notification</strong>
            <small>Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    .checkbox-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-check-input {
        width: 1.25em;
        height: 1.25em;
        cursor: pointer;
    }

    .table thead th {
        vertical-align: middle;
    }

    .bg-light-primary {
        background-color: rgba(var(--theme-default-rgb), 0.05);
    }

    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>

<script>
    let toastInstance;

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const titleEl = document.getElementById('toast-title');
        const messageEl = document.getElementById('toast-message');
        if (!toastInstance) toastInstance = new bootstrap.Toast(toastEl);

        toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');
        if (type === 'success') {
            toastEl.classList.add('bg-success', 'text-white');
            titleEl.innerText = 'Updated';
        } else {
            toastEl.classList.add('bg-danger', 'text-white');
            titleEl.innerText = 'Error';
        }
        messageEl.innerText = message;
        toastInstance.show();
    }

    $(document).ready(function() {
        // Handle Individual Checkbox Change
        $(document).on('change', '.permission-checkbox', function() {
            submitPermissions();
        });

        // Handle Row Select All
        $('.group-row-select').on('change', function() {
            $(this).closest('tr').find('.permission-checkbox').prop('checked', $(this).is(':checked'));
            submitPermissions();
        });

        // Select All Button
        $('#select-all-btn').on('click', function() {
            $('.permission-checkbox, .group-row-select').prop('checked', true);
            submitPermissions();
        });

        function submitPermissions() {
            const selectedPermissions = [];
            $('.permission-checkbox:checked').each(function() {
                selectedPermissions.push($(this).val());
            });

            $.ajax({
                url: "{{ route('admin.roles.permissions.update', $role->id) }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    permissions: selectedPermissions
                },
                success: function(response) {
                    showToast('Permissions synchronized successfully.');
                },
                error: function() {
                    showToast('Failed to update permissions.', 'error');
                }
            });
        }
    });
</script>
@endsection