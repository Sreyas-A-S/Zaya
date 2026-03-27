@extends('layouts.client')

@section('title', 'Client Profile')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 md:gap-8 md:mb-8 mb-5">
    <!-- Left Column -->
    <div id="col-left" class="lg:col-span-5 xl:col-span-4 space-y-5 md:space-y-8">
        <!-- Identity Hub -->
        <div id="section-identity" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <div class="flex justify-between items-center mb-6">
                <h2 id="client_panel_identity_hub_title" class="text-xl font-medium font-sans! text-secondary" data-i18n="{{ $site_settings['client_panel_identity_hub_title'] ?? 'Identity Hub' }}">{{ __($site_settings['client_panel_identity_hub_title'] ?? 'Identity Hub') }}</h2>
            </div>

            @php
                $profile = $user->patient ?? $user->practitioner ?? $user->doctor ?? $user->mindfulnessPractitioner ?? $user->yogaTherapist ?? $user->translator ?? null;
            @endphp
            <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-6">
                <div>
                    <p id="client_panel_age_label" class="text-base text-gray-400 mb-1" data-i18n="{{ $site_settings['client_panel_age_label'] ?? 'Age' }}">{{ __($site_settings['client_panel_age_label'] ?? 'Age') }}</p>
                    <p class="text-base font-normal text-gray-800">{{ $profile->age ?? __($site_settings['client_panel_not_set'] ?? 'Not set') }} {{ isset($profile->age) ? __($site_settings['client_panel_years'] ?? 'Years') : '' }}</p>
                </div>
                <div>
                    <p id="client_panel_gender_label" class="text-base text-gray-400 mb-1" data-i18n="{{ $site_settings['client_panel_gender_label'] ?? 'Gender' }}">{{ __($site_settings['client_panel_gender_label'] ?? 'Gender') }}</p>
                    <p class="text-base font-normal text-gray-800">{{ ucfirst(__($profile->gender ?? ($user->gender ?? ($site_settings['client_panel_not_set'] ?? 'Not set')))) }}</p>
                </div>
                <div class="col-span-2">
                    <p id="client_panel_dob_label" class="text-base text-gray-400 mb-1" data-i18n="{{ $site_settings['client_panel_dob_label'] ?? 'DOB' }}">{{ __($site_settings['client_panel_dob_label'] ?? 'DOB') }}</p>
                    <p class="text-base font-normal text-gray-800">{{ (isset($profile->dob) && $profile->dob) ? (\Carbon\Carbon::parse($profile->dob)->translatedFormat('M d, Y')) : __($site_settings['client_panel_not_set'] ?? 'Not set') }}</p>
                </div>
            </div>

            <hr class="border-[#DDDDDD] mb-6">

            <div class="space-y-6">
                <div>
                    <p id="client_panel_email_label" class="text-base text-gray-400 mb-1" data-i18n="{{ $site_settings['client_panel_email_label'] ?? 'Email' }}">{{ __($site_settings['client_panel_email_label'] ?? 'Email') }}</p>
                    <p class="text-base font-normal text-gray-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p id="client_panel_phone_label" class="text-base text-gray-400 mb-1" data-i18n="{{ $site_settings['client_panel_phone_label'] ?? 'Phone' }}">{{ __($site_settings['client_panel_phone_label'] ?? 'Phone') }}</p>
                    <p class="text-base font-normal text-gray-800">{{ $profile->phone ?? ($user->phone ?? __($site_settings['client_panel_not_set'] ?? 'Not set')) }}</p>
                </div>
                <div>
                    <p id="client_panel_address_label" class="text-base text-gray-400 mb-1" data-i18n="{{ $site_settings['client_panel_address_label'] ?? 'Address' }}">{{ __($site_settings['client_panel_address_label'] ?? 'Address') }}</p>
                    <p class="text-base font-normal text-gray-800 leading-snug">{{ $profile->address ?? ($profile->city_state ?? __($site_settings['client_panel_location_not_set'] ?? 'Location not set')) }}</p>
                </div>
            </div>
        </div>

        <!-- Transaction Vault Snippet (Only for Clients) -->
        @if($user->role === 'client' || $user->role === 'patient')
        <div id="section-transactions" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <h2 id="client_panel_transaction_vault_title" class="text-xl font-sans! font-medium text-secondary mb-6" data-i18n="{{ $site_settings['client_panel_transaction_vault_title'] ?? 'Transaction Vault' }}">{{ __($site_settings['client_panel_transaction_vault_title'] ?? 'Transaction Vault') }}</h2>
            <div class="space-y-5">
                @forelse($invoices as $invoice)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-normal text-gray-800 mb-0.5">{{ __($site_settings['client_panel_invoice_hash'] ?? 'Invoice #') }}{{ $invoice->invoice_no }}</p>
                        <p class="text-xs text-gray-400">{{ $invoice->created_at->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ $invoice->razorpay_payment_url }}" target="_blank"
                        class="px-3 py-1 bg-[#EEF2EF] text-[#2B4C3B] text-sm font-normal rounded-full" data-i18n="{{ $site_settings['client_panel_open_invoice'] ?? 'Open' }}">{{ __($site_settings['client_panel_open_invoice'] ?? 'Open') }}</a>
                </div>
                @empty
                <p id="client_panel_no_recent_invoices" class="text-center text-gray-500 text-xs py-4" data-i18n="{{ $site_settings['client_panel_no_recent_invoices'] ?? 'No recent invoices.' }}">{{ __($site_settings['client_panel_no_recent_invoices'] ?? 'No recent invoices.') }}</p>
                @endforelse
            </div>
            <div class="mt-6 text-center">
                <a id="client_panel_see_all" href="{{ route('transactions.index') }}" class="text-xs text-gray-400 hover:text-gray-800 font-normal tracking-wide" data-i18n="{{ $site_settings['client_panel_see_all'] ?? 'See all' }}">{{ __($site_settings['client_panel_see_all'] ?? 'See all') }}</a>
            </div>
        </div>
        @endif

        @if(in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist']))
        <!-- Practitioner Referrals -->
        <div id="section-referrals" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <h2 class="text-xl font-sans! font-medium text-secondary mb-6 flex items-center gap-2">
                <i class="ri-user-shared-line"></i> My Referrals
            </h2>
            <div class="space-y-5">
                @forelse($referrals as $ref)
                <div class="flex justify-between items-center pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                    <div>
                        <p class="text-sm font-bold text-gray-800 mb-0.5">{{ $ref->user->name }}</p>
                        <p class="text-[10px] text-gray-400 uppercase font-bold">To: {{ $ref->referredTo->name }}</p>
                    </div>
                    <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase {{ $ref->status === 'paid' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }}">
                        {{ $ref->status }}
                    </span>
                </div>
                @empty
                <p class="text-center text-gray-500 text-xs py-4">No recent referrals.</p>
                @endforelse
            </div>
        </div>

        <!-- Client Data Access -->
        <div id="section-client-access" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <h2 class="text-xl font-sans! font-medium text-secondary mb-6 flex items-center gap-2">
                <i class="ri-shield-user-line"></i> Client Access
            </h2>
            <div class="space-y-5">
                @forelse($dataAccessRequests as $dar)
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                            <i class="ri-user-line text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $dar->client->name }}</p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Since: {{ $dar->approved_at->format('M d') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('client.profile.view', $dar->client_id) }}" class="px-3 py-1 bg-secondary text-white text-xs font-bold rounded-full">View</a>
                </div>
                @empty
                <p class="text-center text-gray-500 text-xs py-4">No active client data permissions.</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column -->
    <div id="col-right" class="lg:col-span-7 xl:col-span-8 space-y-5 md:space-y-8">
        <!-- Consultations -->
        <div id="section-consultations" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <h2 id="client_panel_consultations_title" class="text-xl  font-sans! font-medium text-secondary mb-5" data-i18n="{{ $site_settings['client_panel_consultations_title'] ?? 'Consultations' }}">{{ __($site_settings['client_panel_consultations_title'] ?? 'Consultations') }}</h2>

            <!-- Tabs -->
            <div class="flex space-x-2 mb-4">
                <button id="tab-upcoming" onclick="switchConsultationTab('upcoming')"
                    class="px-4 py-1.5 w-1/2 text-center bg-[#CFFAD8] text-[#2FA749] text-sm font-normal rounded-lg transition-all cursor-pointer" data-i18n="Upcoming">Upcoming</button>
                <button id="tab-completed" onclick="switchConsultationTab('completed')"
                    class="px-4 py-1.5 w-1/2 text-center bg-[#F9F9F9] text-[#8C8C8C] text-sm font-normal rounded-lg transition-all cursor-pointer" data-i18n="Completed">Completed</button>
            </div>

            <!-- Upcoming Sessions -->
            <div id="content-upcoming" class="space-y-6">
                @forelse($upcomingBookings as $booking)
                <div class="flex gap-2 justify-between items-center">
                    <div>
                        <div class="flex flex-wrap items-center space-x-2 mb-1">
                            @php
                            $sNames = [];
                            foreach($booking->service_ids ?? [] as $sid) {
                            if(isset($allServices[$sid])) $sNames[] = $allServices[$sid]->title;
                            }
                            @endphp
                            <p class="text-base font-normal text-gray-800">{{ implode(', ', $sNames) }}</p>
                            <span class="text-gray-800 text-base">•</span>
                            <p class="text-xs text-gray-600 font-normal">
                                @if($user->role === 'client' || $user->role === 'patient')
                                    ({{ __($site_settings['client_panel_session_with'] ?? 'Session with') }} {{ $booking->practitioner->user->name ?? 'Practitioner' }})
                                @else
                                    ({{ __($site_settings['client_panel_client_label'] ?? 'Client') }}: {{ $booking->user->name ?? 'Patient' }})
                                @endif
                            </p>
                        </div>
                        <p class="text-xs text-gray-400">{{ $booking->booking_date->translatedFormat('M d, Y') }} - {{ $booking->booking_time }}</p>
                    </div>
                    <span class="px-3 py-1 bg-[#EEF2EF] text-[#2FA749] text-xs font-normal rounded-full capitalize">{{ __($booking->status) }}</span>
                </div>
                @empty
                <p id="client_panel_no_upcoming_msg" class="text-center text-gray-500 text-sm py-6" data-i18n="{{ $site_settings['client_panel_no_upcoming_msg'] ?? 'No upcoming sessions.' }}">{{ __($site_settings['client_panel_no_upcoming_msg'] ?? 'No upcoming sessions.') }}</p>
                @endforelse
            </div>

            <!-- Completed Sessions -->
            <div id="content-completed" class="space-y-6 hidden">
                @forelse($completedBookings as $booking)
                <div class="flex gap-2 justify-between items-center">
                    <div>
                        <div class="flex flex-wrap items-center space-x-2 mb-1">
                            @php
                            $sNames = [];
                            foreach($booking->service_ids ?? [] as $sid) {
                            if(isset($allServices[$sid])) $sNames[] = $allServices[$sid]->title;
                            }
                            @endphp
                            <p class="text-base font-normal text-gray-800">{{ implode(', ', $sNames) }}</p>
                            <span class="text-gray-800 text-base">•</span>
                            <p class="text-xs text-gray-600 font-normal">
                                @if($user->role === 'client' || $user->role === 'patient')
                                    ({{ __($site_settings['client_panel_session_with'] ?? 'Session with') }} {{ $booking->practitioner->user->name ?? 'Practitioner' }})
                                @else
                                    ({{ __($site_settings['client_panel_client_label'] ?? 'Client') }}: {{ $booking->user->name ?? 'Patient' }})
                                @endif
                            </p>
                        </div>
                        <p class="text-xs text-gray-400">{{ $booking->booking_date->translatedFormat('M d, Y') }} - {{ $booking->booking_time }}</p>
                    </div>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-normal rounded-full capitalize">{{ __('Completed') }}</span>
                </div>
                @empty
                <p id="client_panel_no_completed_msg" class="text-center text-gray-500 text-sm py-10" data-i18n="{{ $site_settings['client_panel_no_completed_msg'] ?? 'No completed sessions recently.' }}">{{ __($site_settings['client_panel_no_completed_msg'] ?? 'No completed sessions recently.') }}</p>
                @endforelse
            </div>
            <a id="client_panel_view_all_bookings" href="{{ route('bookings.index') }}" class="block text-center text-sm font-medium text-secondary hover:underline pt-4" data-i18n="{{ $site_settings['client_panel_view_all_bookings'] ?? 'View All Bookings' }}">{{ __($site_settings['client_panel_view_all_bookings'] ?? 'View All Bookings') }}</a>
        </div>

        @if($user->role === 'client' || $user->role === 'patient')
        <!-- Clinical Document Portal -->
        <div id="section-clinical" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 id="client_panel_clinical_portal_title" class="text-xl font-sans! font-medium text-secondary" data-i18n="{{ $site_settings['client_panel_clinical_portal_title'] ?? 'Clinical Document Portal' }}">{{ __($site_settings['client_panel_clinical_portal_title'] ?? 'Clinical Document Portal') }}</h2>
                <a href="{{ route('health-journey.index') }}" class="text-xs text-secondary font-bold hover:underline flex items-center gap-1">
                    Manage All <i class="ri-arrow-right-s-line"></i>
                </a>
            </div>

            <!-- Compact Upload Area -->
            <form id="upload-form" enctype="multipart/form-data">
                @csrf
                <input type="file" id="document-input" name="document" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <div id="drop-zone"
                    class="border-2 border-dashed border-[#8FC0A8] rounded-xl p-6 text-center bg-gray-50/30 mb-6 cursor-pointer hover:bg-white hover:border-secondary transition-all group">
                    <i class="ri-upload-2-line text-2xl text-secondary mb-2 block group-hover:scale-110 transition-transform"></i>
                    <p class="text-xs text-gray-500 mb-3">Upload clinical documents (Max 20MB)</p>
                    <button type="button" id="client_panel_upload_btn"
                        class="inline-flex items-center justify-center px-4 py-2 bg-secondary text-white rounded-full text-[10px] font-bold hover:bg-primary transition-all shadow-md shadow-secondary/10">
                        Upload Now
                    </button>
                </div>
            </form>

            <!-- Recent Documents (Swiper) -->
            <div class="swiper document-swiper pb-4 w-full">
                <div class="swiper-wrapper" id="documents-wrapper">
                    @forelse($clinicalDocuments as $doc)
                    <div class="swiper-slide w-[120px]! bg-white px-3 py-4 rounded-xl relative flex flex-col items-center justify-center border border-gray-100 hover:shadow-lg transition-all" id="doc-{{ $doc->id }}">
                        <button onclick="deleteDocument({{ $doc->id }})"
                            class="absolute top-1 right-1 w-6 h-6 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-all z-10">
                            <i class="ri-delete-bin-line text-[10px]"></i>
                        </button>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="flex flex-col items-center justify-center w-full">
                            @php
                                $bgColor = 'bg-blue-50';
                                $textColor = 'text-blue-500';
                                $icon = 'ri-file-text-fill';
                                
                                if (in_array(strtolower($doc->file_type), ['pdf'])) {
                                    $bgColor = 'bg-red-50';
                                    $textColor = 'text-red-500';
                                    $icon = 'ri-file-pdf-fill';
                                } elseif (in_array(strtolower($doc->file_type), ['jpg', 'jpeg', 'png'])) {
                                    $bgColor = 'bg-green-50';
                                    $textColor = 'text-green-500';
                                    $icon = 'ri-image-fill';
                                }
                            @endphp
                            <div class="w-10 h-10 {{ $bgColor }} {{ $textColor }} flex items-center justify-center rounded-lg mb-2">
                                <i class="{{ $icon }} text-lg"></i>
                            </div>
                            <p class="text-[10px] font-bold text-gray-800 truncate w-full text-center px-1" title="{{ $doc->file_name }}">
                                {{ $doc->file_name }}</p>
                        </a>
                    </div>
                    @empty
                    <p id="no-docs-msg" class="text-center text-gray-400 text-[10px] py-4 w-full">{{ __('No documents yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Reviews -->
<div id="section-reviews" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12 mb-5 md:mb-8">
    <h2 id="client_panel_your_reviews_title" class="text-xl font-medium font-sans! text-secondary mb-6" data-i18n="{{ $site_settings['client_panel_your_reviews_title'] ?? 'Your Reviews' }}">{{ __($site_settings['client_panel_your_reviews_title'] ?? 'Your Reviews') }}</h2>
    <div class="space-y-6">
        @forelse($reviews as $review)
        <div class="border-b border-[#DDDDDD] pb-6">
            <div class="flex items-center space-x-3 mb-3">
                <h3 class="font-sans! text-base font-medium text-gray-800">{{ $review->practitioner->user->name }}</h3>
                <span class="text-xs md:text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                {{-- <div class="flex items-center gap-3 ml-auto shrink-0">
                    <button
                        class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center text-[#2B4C3B] hover:bg-gray-50 transition-colors cursor-pointer"><i
                            class="ri-pencil-line"></i></button>
                    <button
                        class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center bg-red-50 text-red-400 hover:bg-red-100 transition-colors cursor-pointer"><i
                            class="ri-delete-bin-line"></i></button>
                </div> --}}
            </div>
            <div class="flex flex-wrap gap-2 justify-between items-start">
                <div>
                    <p class="text-sm text-gray-600 mb-2.5 leading-relaxed">{{ __($site_settings['client_panel_comment_label'] ?? 'Comment') }}: "{{ $review->review }}"</p>
                    <div class="flex items-center">
                        <span id="client_panel_rating_label" class="text-sm text-gray-500 mr-3" data-i18n="{{ $site_settings['client_panel_rating_label'] ?? 'Rating' }}">{{ __($site_settings['client_panel_rating_label'] ?? 'Rating') }}:</span>
                        <div class="flex text-[#FFD166] space-x-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <=floor($review->rating))
                                <i class="ri-star-fill text-sm"></i>
                                @elseif($i - 0.5 <= $review->rating)
                                    <i class="ri-star-half-fill text-sm"></i>
                                    @else
                                    <i class="ri-star-line text-sm text-gray-300"></i>
                                    @endif
                                    @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
                @empty
                <p id="client_panel_no_reviews_msg" class="text-center text-gray-500 text-sm py-6" data-i18n="{{ $site_settings['client_panel_no_reviews_msg'] ?? 'You haven\'t written any reviews yet.' }}">{{ __($site_settings['client_panel_no_reviews_msg'] ?? 'You haven\'t written any reviews yet.') }}</p>
                @endforelse
    </div>
</div>

@if($user->role === 'client' || $user->role === 'patient')
<!-- GDPR Center -->
<div id="section-gdpr"
    class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12 flex flex-col md:flex-row flex-wrap gap-4 items-center justify-between">
    <div class="flex flex-1 items-center space-x-3">
        <i class="ri-shield-check-fill text-secondary text-xl"></i>
        <h2 id="client_panel_gdpr_title" class="text-sm md:text-lg font-sans! font-medium text-secondary leading-snug" data-i18n="{{ $site_settings['client_panel_gdpr_title'] ?? 'General Data Protection Regulation Control Center' }}">{{ __($site_settings['client_panel_gdpr_title'] ?? 'General Data Protection Regulation Control Center') }}</h2>
    </div>
    <div class="flex flex-1 items-center justify-end space-x-4 lg:border-l lg:border-gray-100 lg:h-8">
        <span id="client_panel_data_sharing_label" class="text-base md:text-lg text-gray-600" data-i18n="{{ $site_settings['client_panel_data_sharing_label'] ?? 'Data sharing with Practitioners' }}">{{ __($site_settings['client_panel_data_sharing_label'] ?? 'Data sharing with Practitioners') }}</span>
        <!-- Toggle Switch -->
        <button
            id="gdpr-toggle"
            onclick="toggleConsent(this)"
            class="w-10 h-5 {{ ($user->patient->data_sharing_consent ?? false) ? 'bg-secondary' : 'bg-gray-300' }} rounded-full relative flex items-center transition-colors cursor-pointer">
            <div
                class="w-4 h-4 bg-white rounded-full absolute left-0.5 shadow-sm transition-transform duration-300 {{ ($user->patient->data_sharing_consent ?? false) ? 'translate-x-5' : '' }}">
            </div>
        </button>
    </div>
</div>

<!-- GDPR Confirmation Modal -->
<div id="gdpr-modal" class="fixed inset-0 z-[100002] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeGdprModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-[32px] p-8 md:p-10 max-w-[450px] w-[90%] text-center shadow-[0_20px_50px_rgba(0,0,0,0.1)] transform transition-all duration-300 scale-90">
        <!-- Close Button -->
        <button onclick="closeGdprModal()" class="absolute top-6 right-8 text-gray-300 hover:text-gray-500 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>

        <div class="mb-6">
            <div class="w-16 h-16 bg-[#EEF2EF] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-shield-user-line text-secondary text-3xl"></i>
            </div>
            <h3 id="client_panel_gdpr_modal_title" class="text-xl font-bold font-sans! text-secondary mb-2" data-i18n="{{ $site_settings['client_panel_gdpr_modal_title'] ?? 'Update Data Sharing?' }}">{{ __($site_settings['client_panel_gdpr_modal_title'] ?? 'Update Data Sharing?') }}</h3>
            <p id="gdpr-modal-text" class="text-gray-500 text-sm leading-relaxed">
                Are you sure you want to change your data sharing preferences?
            </p>
        </div>

        <div class="flex gap-4">
            <button onclick="closeGdprModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 rounded-full font-medium hover:bg-gray-50 transition-colors" data-i18n="{{ $site_settings['client_panel_gdpr_cancel_btn'] ?? 'Cancel' }}">
                {{ __($site_settings['client_panel_gdpr_cancel_btn'] ?? 'Cancel') }}
            </button>
            <button id="confirm-gdpr-btn" class="flex-1 px-6 py-3 bg-secondary text-white rounded-full font-medium hover:bg-opacity-90 transition-all" data-i18n="{{ $site_settings['client_panel_gdpr_confirm_btn'] ?? 'Confirm' }}">
                {{ __($site_settings['client_panel_gdpr_confirm_btn'] ?? 'Confirm') }}
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-[100002] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-[32px] p-8 md:p-10 max-w-[450px] w-[90%] text-center shadow-[0_20px_50px_rgba(0,0,0,0.1)] transform transition-all duration-300 scale-90">
        <!-- Close Button -->
        <button onclick="closeDeleteModal()" class="absolute top-6 right-8 text-gray-300 hover:text-gray-500 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>

        <div class="mb-6">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-delete-bin-line text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold font-sans! text-secondary mb-2">{{ __('Delete Item?') }}</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                {{ __('Are you sure you want to delete this? This action cannot be undone.') }}
            </p>
        </div>

        <div class="flex gap-4">
            <button onclick="closeDeleteModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 rounded-full font-medium hover:bg-gray-50 transition-colors">
                {{ __('Cancel') }}
            </button>
            <button id="confirm-delete-btn" class="flex-1 px-6 py-3 bg-red-500 text-white rounded-full font-medium hover:bg-opacity-90 transition-all">
                {{ __('Delete') }}
            </button>
        </div>
    </div>
</div>

<!-- Upload Preview Modal -->
<div id="upload-preview-modal" class="fixed inset-0 z-[100002] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeUploadPreviewModal()"></div>
    <div class="relative bg-white rounded-[32px] p-8 md:p-10 max-w-[450px] w-[90%] text-center shadow-[0_20px_50px_rgba(0,0,0,0.1)] transform transition-all duration-300 scale-90">
        <button onclick="closeUploadPreviewModal()" class="absolute top-6 right-8 text-gray-300 hover:text-gray-500 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>
        <div class="mb-6">
            <div id="preview-icon-bg" class="w-16 h-16 bg-[#EEF2EF] rounded-full flex items-center justify-center mx-auto mb-4">
                <i id="preview-icon" class="ri-file-upload-line text-secondary text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-secondary mb-2">{{ __('Confirm Upload') }}</h3>
            <p id="preview-filename" class="text-gray-800 font-semibold text-sm mb-1 truncate px-4"></p>
            <p id="preview-filesize" class="text-gray-400 text-xs mb-4"></p>
            <div id="upload-progress-container" class="hidden w-full bg-gray-100 rounded-full h-2 mb-4 overflow-hidden">
                <div id="upload-progress-bar" class="bg-secondary h-full w-0 transition-all duration-300"></div>
            </div>
            <p id="upload-percentage" class="hidden text-secondary text-xs font-bold mb-4">0%</p>
        </div>
        <div id="preview-actions" class="flex gap-4">
            <button onclick="closeUploadPreviewModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 rounded-full font-medium hover:bg-gray-50 transition-colors">
                {{ __('Cancel') }}
            </button>
            <button id="confirm-upload-btn" class="flex-1 px-6 py-3 bg-secondary text-white rounded-full font-medium hover:bg-opacity-90 transition-all">
                {{ __('Upload Now') }}
            </button>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    function switchConsultationTab(tab) {
        const upcomingBtn = document.getElementById('tab-upcoming');
        const completedBtn = document.getElementById('tab-completed');
        const upcomingContent = document.getElementById('content-upcoming');
        const completedContent = document.getElementById('content-completed');

        const activeBtnClasses = 'px-4 py-1.5 w-1/2 text-center bg-[#CFFAD8] text-[#2FA749] text-sm font-normal rounded-lg transition-all cursor-pointer';
        const inactiveBtnClasses = 'px-4 py-1.5 w-1/2 text-center bg-[#F9F9F9] text-[#8C8C8C] text-sm font-normal rounded-lg transition-all cursor-pointer';

        if (tab === 'upcoming') {
            upcomingBtn.className = activeBtnClasses;
            completedBtn.className = inactiveBtnClasses;
            upcomingContent.classList.remove('hidden');
            completedContent.classList.add('hidden');
        } else {
            completedBtn.className = activeBtnClasses;
            upcomingBtn.className = inactiveBtnClasses;
            completedContent.classList.remove('hidden');
            upcomingContent.classList.add('hidden');
        }
    }

    function switchMobileTab(selectedTab) {
        const tabMapping = {
            'dashboard': 'client_panel_sidebar_dashboard_mobile',
            'bookings': 'client_panel_sidebar_bookings_mobile',
            'transactions': 'client_panel_sidebar_transaction_vault_mobile'
        };

        Object.keys(tabMapping).forEach(tabKey => {
            const btnId = tabMapping[tabKey];
            const btn = document.getElementById(btnId);
            if (btn) {
                btn.className = (tabKey === selectedTab) ?
                    "leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1" :
                    "leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors";
            }
        });

        const allSections = [
            'section-identity', 'section-gdpr',
            'section-reviews',
            'section-consultations', 'section-transactions',
            'col-left', 'col-right'
        ];

        allSections.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('mobile-hidden');
        });

        const tabMap = {
            'dashboard': [
                'section-identity', 'section-gdpr',
                'section-reviews',
                'section-consultations', 'section-transactions',
                'col-left', 'col-right'
            ],
            'transactions': ['section-transactions', 'col-left']
        };

        if (tabMap[selectedTab]) {
            tabMap[selectedTab].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('mobile-hidden');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Handle tab switching via URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab && ['dashboard', 'transactions'].includes(tab)) {
            switchMobileTab(tab);
        }

        // Clinical Portal JS
        const docInput = document.getElementById('document-input');
        const uploadBtn = document.getElementById('client_panel_upload_btn');
        const dropZone = document.getElementById('drop-zone');
        let documentSwiper;

        if (typeof Swiper !== 'undefined') {
            documentSwiper = new Swiper('.document-swiper', {
                slidesPerView: 'auto',
                spaceBetween: 12,
                grabCursor: true,
                freeMode: true,
            });
        }

        if (uploadBtn && docInput) {
            uploadBtn.addEventListener('click', () => docInput.click());
        }

        if (dropZone && docInput) {
            dropZone.addEventListener('click', (e) => {
                if (e.target !== uploadBtn && !uploadBtn.contains(e.target)) {
                    docInput.click();
                }
            });

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('bg-white', 'border-secondary');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('bg-white', 'border-secondary');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('bg-white', 'border-secondary');
                if (e.dataTransfer.files.length) {
                    openUploadPreviewModal(e.dataTransfer.files[0]);
                }
            });
        }

        if (docInput) {
            docInput.addEventListener('change', () => {
                if (docInput.files.length) {
                    openUploadPreviewModal(docInput.files[0]);
                }
            });
        }

        function uploadFile(file) {
            const formData = new FormData();
            formData.append('document', file);
            formData.append('_token', '{{ csrf_token() }}');

            const progressContainer = document.getElementById('upload-progress-container');
            const progressBar = document.getElementById('upload-progress-bar');
            const percentageText = document.getElementById('upload-percentage');
            const actions = document.getElementById('preview-actions');

            progressContainer.classList.remove('hidden');
            percentageText.classList.remove('hidden');
            actions.classList.add('hidden');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ route('clinical-documents.upload') }}", true);

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    percentageText.textContent = percentComplete + '%';
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    if (data.document) {
                        addDocumentToUI(data.document, data.url);
                        if (window.showZayaToast) showZayaToast('Document uploaded successfully.', 'Clinical Portal');
                        if (document.getElementById('no-docs-msg')) document.getElementById('no-docs-msg').remove();
                    }
                    closeUploadPreviewModal();
                } else {
                    const data = JSON.parse(xhr.responseText);
                    if (window.showZayaToast) showZayaToast(data.message || 'Upload failed', 'Error', 'error');
                    closeUploadPreviewModal();
                }
            };

            xhr.onerror = function() {
                if (window.showZayaToast) showZayaToast('An error occurred during upload.', 'Error', 'error');
                closeUploadPreviewModal();
            };

            xhr.send(formData);
        }

        function openUploadPreviewModal(file) {
            const modal = document.getElementById('upload-preview-modal');
            const content = modal.querySelector('.relative.bg-white');
            const filenameEl = document.getElementById('preview-filename');
            const filesizeEl = document.getElementById('preview-filesize');
            const iconBg = document.getElementById('preview-icon-bg');
            const icon = document.getElementById('preview-icon');
            const confirmBtn = document.getElementById('confirm-upload-btn');
            
            document.getElementById('upload-progress-container').classList.add('hidden');
            document.getElementById('upload-percentage').classList.add('hidden');
            document.getElementById('preview-actions').classList.remove('hidden');

            filenameEl.textContent = file.name;
            filesizeEl.textContent = formatFileSize(file.size);

            const ext = file.name.split('.').pop().toLowerCase();
            iconBg.className = "w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-[#EEF2EF]";
            if (ext === 'pdf') { iconBg.classList.add('bg-red-50'); icon.className = "ri-file-pdf-line text-red-500 text-3xl"; }
            else if (['jpg', 'jpeg', 'png'].includes(ext)) { iconBg.classList.add('bg-green-50'); icon.className = "ri-image-line text-green-500 text-3xl"; }
            else { icon.className = "ri-file-text-line text-secondary text-3xl"; }

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');
            setTimeout(() => content.classList.replace('scale-90', 'scale-100'), 10);

            confirmBtn.onclick = () => uploadFile(file);
        }

        window.closeUploadPreviewModal = function() {
            const modal = document.getElementById('upload-preview-modal');
            const content = modal.querySelector('.relative.bg-white');
            content.classList.replace('scale-100', 'scale-90');
            setTimeout(() => {
                modal.classList.replace('opacity-100', 'opacity-0');
                modal.classList.add('pointer-events-none');
                if (docInput) docInput.value = '';
            }, 300);
        };

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024, sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function addDocumentToUI(doc, url) {
            const wrapper = document.getElementById('documents-wrapper');
            const slide = document.createElement('div');
            slide.className = 'swiper-slide w-[120px]! bg-white px-3 py-4 rounded-xl relative flex flex-col items-center justify-center border border-gray-100 hover:shadow-lg transition-all';
            slide.id = `doc-${doc.id}`;

            let bgColor = 'bg-blue-50', textColor = 'text-blue-500', icon = 'ri-file-text-fill';
            const ext = doc.file_type.toLowerCase();
            if (ext === 'pdf') { bgColor = 'bg-red-50'; textColor = 'text-red-500'; icon = 'ri-file-pdf-fill'; }
            else if (['jpg', 'jpeg', 'png'].includes(ext)) { bgColor = 'bg-green-50'; textColor = 'text-green-500'; icon = 'ri-image-fill'; }

            slide.innerHTML = `
                <button onclick="deleteDocument(${doc.id})" class="absolute top-1 right-1 w-6 h-6 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-all z-10"><i class="ri-delete-bin-line text-[10px]"></i></button>
                <a href="${url}" target="_blank" class="flex flex-col items-center justify-center w-full">
                    <div class="w-10 h-10 ${bgColor} ${textColor} flex items-center justify-center rounded-lg mb-2"><i class="${icon} text-lg"></i></div>
                    <p class="text-[10px] font-bold text-gray-800 truncate w-full text-center px-1">${doc.file_name}</p>
                </a>`;
            wrapper.prepend(slide);
            if (documentSwiper) documentSwiper.update();
        }
    });

    function deleteDocument(id) {
        if (confirm('Are you sure you want to delete this document?')) {
            fetch(`{{ url('/clinical-documents') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(`doc-${id}`).remove();
                if (window.showZayaToast) showZayaToast('Document deleted successfully.', 'Clinical Portal');
            });
        }
    }

    function openGdprModal(callback) {
        const modal = document.getElementById('gdpr-modal');
        const content = modal.querySelector('.relative.bg-white');
        const confirmBtn = document.getElementById('confirm-gdpr-btn');

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');

        setTimeout(() => {
            content.classList.remove('scale-90');
            content.classList.add('scale-100');
        }, 10);

        confirmBtn.onclick = () => {
            callback();
            closeGdprModal();
        };
    }

    function closeGdprModal() {
        const modal = document.getElementById('gdpr-modal');
        const content = modal.querySelector('.relative.bg-white');

        content.classList.remove('scale-100');
        content.classList.add('scale-90');

        setTimeout(() => {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100');
        }, 300);
    }

    function toggleConsent(btn) {
        const dot = btn.querySelector('div');
        const isCurrentlyActive = btn.classList.contains('bg-secondary');
        const newState = !isCurrentlyActive;

        const actionText = newState ?
            "By enabling this, your health records and clinical documents will be accessible to practitioners during consultations." :
            "By disabling this, practitioners will no longer have access to your health records and clinical documents.";

        document.getElementById('gdpr-modal-text').innerText = actionText;

        openGdprModal(() => {
            // Optimistic UI update
            if (newState) {
                btn.classList.remove('bg-gray-300');
                btn.classList.add('bg-secondary');
                dot.classList.add('translate-x-5');
            } else {
                btn.classList.remove('bg-secondary');
                btn.classList.add('bg-gray-300');
                dot.classList.remove('translate-x-5');
            }

            fetch("{{ route('profile.updateConsent') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        consent: newState
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    if (window.showZayaToast) {
                        showZayaToast('Data sharing preferences updated successfully.', 'Privacy Settings');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // Revert UI on error
                    if (isCurrentlyActive) {
                        btn.classList.remove('bg-gray-300');
                        btn.classList.add('bg-secondary');
                        dot.classList.add('translate-x-5');
                    } else {
                        btn.classList.remove('bg-secondary');
                        btn.classList.add('bg-gray-300');
                        dot.classList.remove('translate-x-5');
                    }
                });
        });
    }
</script>
@endsection
