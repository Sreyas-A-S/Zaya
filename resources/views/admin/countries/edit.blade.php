@extends('admin.layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">Edit Country</h2>

    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary mb-3">
        Back to List
    </a>

    <form action="{{ route('admin.countries.update', $country->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Country Code</label>
            <input type="text" 
                   name="code" 
                   value="{{ old('code', $country->code) }}"
                   class="form-control @error('code') is-invalid @enderror">

            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Country Name</label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name', $country->name) }}"
                   class="form-control @error('name') is-invalid @enderror">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Country Flag (Emoji)</label>
            <input type="text" 
                   name="flag" 
                   value="{{ old('flag', $country->flag) }}"
                   class="form-control">
        </div>

        <button type="submit" class="btn btn-success">
            Update Country
        </button>
    </form>
</div>

@endsection
