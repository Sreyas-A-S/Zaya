@extends('layouts.admin')

@section('title', 'Email Logs')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Email Logs</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Logs</li>
                    <li class="breadcrumb-item active">Email Logs</li>
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
                    <h3>System Email History</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="email-logs-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Recipient</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $log->to }}</td>
                                    <td>{{ $log->subject }}</td>
                                    <td>
                                        @if($log->status === 'success' || $log->status === 'sent')
                                            <span class="badge badge-success">Success</span>
                                        @else
                                            <div class="d-flex flex-column">
                                                <span class="badge badge-danger">Failed</span>
                                                @if($log->error_message)
                                                    <small class="text-danger mt-1 text-truncate" style="max-width: 150px;" title="{{ $log->error_message }}">
                                                        {{ $log->error_message }}
                                                    </small>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $log->duration ? $log->duration . 's' : '-' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info viewLog" data-id="{{ $log->id }}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger deleteLog" data-id="{{ $log->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
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

<!-- View Modal -->
<div class="modal fade" id="log-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Log Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="view-modal-content">
                <!-- Content will be loaded via JS -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#email-logs-table').DataTable({
            order: [[0, 'desc']]
        });

        const logs = @json($logs);

        $('body').on('click', '.viewLog', function() {
            const id = $(this).data('id');
            const log = logs.find(l => l.id == id);
            
            if (log) {
                const statusBadge = log.status === 'success' 
                    ? '<span class="badge badge-success text-white">Success</span>' 
                    : '<span class="badge badge-danger text-white">Failed</span>';

                let errorContent = '';
                if (log.error_message) {
                    errorContent = `
                        <div class="col-md-12 mt-3">
                            <div class="p-3 rounded" style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                                <label class="fw-bold text-danger small text-uppercase d-block mb-2">
                                    <i class="fa fa-bug me-1"></i> Technical Cause
                                </label>
                                <div class="bg-white p-2 border rounded font-monospace small text-dark shadow-sm" style="word-break: break-all; white-space: pre-wrap;">
                                    ${log.error_message}
                                </div>
                            </div>
                        </div>
                    `;
                }

                const html = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Recipient</label>
                            <p class="mb-0 text-dark">${log.to}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Date</label>
                            <p class="mb-0 text-dark">${new Date(log.created_at).toLocaleString()}</p>
                        </div>
                        <div class="col-md-8">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Subject</label>
                            <p class="mb-0 text-dark">${log.subject}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Status</label>
                            ${statusBadge}
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Duration</label>
                            <p class="mb-0 text-dark">${log.duration ? log.duration + ' seconds' : 'N/A'}</p>
                        </div>
                        ${errorContent}
                    </div>
                `;
                $('#view-modal-content').html(html);
                $('#log-view-modal').modal('show');
            }
        });

        $('body').on('click', '.deleteLog', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this log?')) {
                $.ajax({
                    url: "{{ url('admin/email-logs') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Error deleting log');
                    }
                });
            }
        });
    });
</script>
@endsection
