@extends('layouts.admin')

@section('title', 'Open Register Link')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Open Register Link</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.forms.index') }}">Forms</a></li>
                    <li class="breadcrumb-item active">#{{ $link->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                    <h3>Link #{{ $link->id }}</h3>
                    <a class="btn btn-secondary" href="{{ route('admin.forms.index') }}">Back</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-2"><strong>User Type:</strong> {{ ucfirst(str_replace('-', ' ', $link->role)) }}</div>
                            <div class="mb-2"><strong>Created By:</strong> {{ $link->creator?->name ?? $link->creator?->email ?? '—' }}</div>
                            <div class="mb-2"><strong>Status:</strong> {{ (strtolower(trim((string) ($link->status ?? 'active'))) === 'active' || (string) $link->status === '1') ? 'Active' : 'Inactive' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Expires At:</strong> {{ optional($link->expires_at)->format('Y-m-d H:i:s') ?? '—' }}</div>
                            <div class="mb-2"><strong>Used At:</strong> {{ optional($link->used_at)->format('Y-m-d H:i:s') ?? '—' }}</div>
                            <div class="mb-2"><strong>Created:</strong> {{ optional($link->created_at)->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-2">URL</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $link->url }}" readonly>
                        <a class="btn btn-primary" href="{{ $link->url }}" target="_blank" rel="noopener">Open</a>
                    </div>

                    <hr>

                    @php
                        $users = collect($link->registeredUsers ?? []);
                        if ($hasUsedByColumn && $link->usedBy) {
                            $users->push($link->usedBy);
                        }
                        $uniqueUsers = $users->unique('id')->values();
                    @endphp

                    <h5 class="mb-2">Registered Users ({{ $uniqueUsers->count() }})</h5>

                    @if($uniqueUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Registered At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($uniqueUsers as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name ?? trim($user->first_name . ' ' . $user->last_name) }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', (string) $user->role)) }}</td>
                                        <td>{{ optional($user->created_at)->format('Y-m-d H:i:s') ?? '—' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light mb-0">No one has registered with this link yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
