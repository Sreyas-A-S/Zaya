@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Notifications</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item active">Notifications</li>
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
                    <h3>Your Notifications</h3>
                    <div>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('admin.notifications.mark-all-as-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-secondary me-2">
                                <i class="fa fa-check-double me-2"></i> Mark all as read
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="notifications-table">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th>Date</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $index => $notification)
                                <tr class="{{ $notification->unread() ? 'table-light fw-bold' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @php
                                            $data = $notification->data;
                                            $message = $data['message'] ?? ($data['title'] ?? 'New Notification');
                                        @endphp
                                        {{ $message }}
                                    </td>
                                    <td>
                                        @if($notification->unread())
                                            <span class="badge badge-warning">Unread</span>
                                        @else
                                            <span class="badge badge-light text-dark">Read</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if($notification->unread())
                                            <button class="btn btn-sm btn-info markAsRead" data-id="{{ $notification->id }}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            @endif
                                            <button class="btn btn-sm btn-danger deleteNotification" data-id="{{ $notification->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $notifications->links() }}
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
        $('#notifications-table').DataTable({
            order: [[1, 'desc']],
            paging: false,
            info: false
        });

        $('body').on('click', '.markAsRead', function() {
            const btn = $(this);
            const id = btn.data('id');
            
            $.ajax({
                url: "{{ url('admin/notifications') }}/" + id + "/mark-as-read",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    showToast('Error marking as read', 'error');
                }
            });
        });

        $('body').on('click', '.deleteNotification', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this notification?')) {
                $.ajax({
                    url: "{{ url('admin/notifications') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        showToast('Error deleting notification', 'error');
                    }
                });
            }
        });
    });
</script>
@endsection
