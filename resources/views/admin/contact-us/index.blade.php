@extends('layouts.admin')

@section('title', 'Contact Page Settings')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Contact Page Settings</h3>
        </div>

        <div class="card-body">
            <form id="contactSettingsForm"
                  action="{{ route('admin.contact-settings.update') }}"
                  method="POST">
                @csrf

                @foreach($settings as $setting)
                    @include('admin.contact-settings.partials.field', ['setting' => $setting])
                @endforeach

                <div class="text-end mt-3">
                    <button type="submit"
                            id="saveBtn"
                            class="btn btn-primary">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
$('#contactSettingsForm').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);
    let btn = $('#saveBtn');

    btn.prop('disabled', true).text('Saving...');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            alert(response.message);
        },
        complete: function() {
            btn.prop('disabled', false).text('Save Settings');
        }
    });
});
</script>
@endsection