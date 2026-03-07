@extends('layouts.admin')

@section('title', 'Newsletter Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Newsletter Subscriptions</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Marketing</li>
                    <li class="breadcrumb-item active">Newsletters</li>
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
                    <h3>Active Subscriptions</h3>
                    <a href="{{ route('admin.newsletters.export') }}" class="btn btn-primary">
                        <i class="fa fa-download me-2"></i> Export to CSV
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="newsletter-table">
                            <thead>
                                <tr>
                                    <th>Date Subscribed</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $sub)
                                <tr>
                                    <td>{{ $sub->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $sub->email }}</td>
                                    <td>
                                        <button class="badge {{ $sub->is_active ? 'badge-success' : 'badge-danger' }} border-0 toggle-status" data-id="{{ $sub->id }}">
                                            {{ $sub->is_active ? 'Active' : 'Unsubscribed' }}
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger deleteSubscription" data-id="{{ $sub->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
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

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#newsletter-table').DataTable({
            order: [[0, 'desc']]
        });

        $('body').on('click', '.toggle-status', function() {
            const btn = $(this);
            const id = btn.data('id');
            
            $.ajax({
                url: "{{ url('admin/newsletters') }}/" + id + "/status",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.is_active) {
                        btn.removeClass('badge-danger').addClass('badge-success').text('Active');
                    } else {
                        btn.removeClass('badge-success').addClass('badge-danger').text('Unsubscribed');
                    }
                    showToast(response.success);
                },
                error: function() {
                    showToast('Error updating status', 'error');
                }
            });
        });

        $('body').on('click', '.deleteSubscription', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this subscription?')) {
                $.ajax({
                    url: "{{ url('admin/newsletters') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        showToast('Error deleting subscription', 'error');
                    }
                });
            }
        });
    });
</script>
@endsection
