@component('mail::message')
# Registration Link Shared

Hello,

You have been invited to join the ZAYA Wellness Collective. Please use the secure link below to complete your registration.

@component('mail::button', ['url' => $link])
Complete Registration
@endcomponent


This link will expire in 7 days.

Thanks,  
{{ config('app.name') }}
@endcomponent
