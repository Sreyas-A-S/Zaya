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
                    <div>
                        <h5>Role: <span class="text-primary">{{ $role->name }}</span></h5>
                        <p class="text-muted mb-0 small">Manage access levels and permissions for this role.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-dark"><i class="fa-solid fa-arrow-left me-2"></i> Back to Roles</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Global Actions -->
                    <div class="d-flex justify-content-end mb-3 gap-2">
                        <button type="button" class="btn btn-sm btn-light-primary" id="select-all-global">Select All</button>
                        <button type="button" class="btn btn-sm btn-light-secondary" id="deselect-all-global">Deselect All</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 20%;" class="align-middle">Module</th>
                                    <th class="text-center align-middle" style="width: 15%;">View</th>
                                    <th class="text-center align-middle" style="width: 15%;">Create</th>
                                    <th class="text-center align-middle" style="width: 15%;">Edit</th>
                                    <th class="text-center align-middle" style="width: 15%;">Delete</th>
                                    <th class="text-center align-middle" style="width: 80px;">
                                        <i class="fa fa-check-double" title="Toggle Row"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $standardActions = ['view', 'create', 'edit', 'delete'];
                                @endphp
                                @foreach($permissions as $group => $groupPermissions)
                                <tr>
                                    <td class="fw-bold text-dark bg-light-primary">{{ ucwords(str_replace(['_', '-'], ' ', $group)) }}</td>

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
                                        <div class="form-check checkbox-primary d-flex justify-content-center mb-0">
                                            <input type="checkbox"
                                                class="form-check-input permission-checkbox"
                                                value="{{ $perm->id }}"
                                                id="perm_{{ $perm->id }}"
                                                {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $perm->id }}"></label>
                                        </div>
                                        @else
                                        <span class="text-muted text-opacity-25"><i class="fa fa-minus"></i></span>
                                        @endif
                                    </td>
                                    @endforeach

                                    <td class="text-center bg-light">
                                        <div class="form-check checkbox-solid-dark d-flex justify-content-center mb-0">
                                            <input type="checkbox" class="form-check-input group-row-select"
                                                id="row_select_{{ $loop->index }}"
                                                title="Select All in {{ $group }}">
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
    .table td {
        vertical-align: middle;
    }

    .form-check-input {
        width: 1.3em;
        height: 1.3em;
        cursor: pointer;
    }

    .bg-light-primary {
        background-color: #f8f9fa !important;
        color: #2c3e50;
    }

    /* Checkbox highlight on hover */
    .form-check:hover .form-check-input {
        border-color: var(--theme-default);
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
            titleEl.innerText = 'Success';
        } else {
            toastEl.classList.add('bg-danger', 'text-white');
            titleEl.innerText = 'Error';
        }
        messageEl.innerText = message;
        toastInstance.show();
    }

    $(document).ready(function() {
        // Initial check for row selectors
        updateRowSelectors();

        // 1. Handle Row Select All
        $('.group-row-select').on('change', function() {
            const isChecked = $(this).is(':checked');
            $(this).closest('tr').find('.permission-checkbox').prop('checked', isChecked);
            submitPermissions();
        });

        // 2. Handle Individual Checkbox Change -> Update Row Selector
        $(document).on('change', '.permission-checkbox', function() {
            const row = $(this).closest('tr');
            const allInRow = row.find('.permission-checkbox');
            const checkedInRow = row.find('.permission-checkbox:checked');
            const rowSelector = row.find('.group-row-select');

            rowSelector.prop('checked', allInRow.length === checkedInRow.length);
            rowSelector.prop('indeterminate', checkedInRow.length > 0 && checkedInRow.length < allInRow.length);
            submitPermissions();
        });

        function updateRowSelectors() {
            $('tbody tr').each(function() {
                const row = $(this);
                const allInRow = row.find('.permission-checkbox');
                const checkedInRow = row.find('.permission-checkbox:checked');
                const rowSelector = row.find('.group-row-select');

                if (allInRow.length > 0) {
                    rowSelector.prop('checked', allInRow.length === checkedInRow.length);
                    rowSelector.prop('indeterminate', checkedInRow.length > 0 && checkedInRow.length < allInRow.length);
                }
            });
        }

        // 3. Global Select/Deselect
        $('#select-all-global').on('click', function() {
            $('.permission-checkbox, .group-row-select').prop('checked', true);
            submitPermissions();
        });

        $('#deselect-all-global').on('click', function() {
            $('.permission-checkbox, .group-row-select').prop('checked', false).prop('indeterminate', false);
            submitPermissions();
        });

        // 4. Auto Sync Function
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
                    showToast('Permissions synced successfully.');
                },
                error: function() {
                    showToast('Failed to sync permissions.', 'error');
                }
            });
        }
    });
</script>
@endsection