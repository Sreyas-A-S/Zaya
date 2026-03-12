    1 @extends('layouts.admin')
     2
     3 @section('title', 'Services Page Settings')
     4
     5 @section('content')
     6 <style>
     7     .nav-pills .nav-link {
     8         color: #555;
     9         border-radius: 8px;
    10         transition: all 0.3s ease;
    11         padding: 12px 20px;
    12         margin-bottom: 5px;
    13     }
    14
    15     .nav-pills .nav-link.active {
    16         background-color: var(--theme-default) !important;
    17         color: #fff !important;
    18     }
    19
    20     .nav-pills .nav-link:hover:not(.active) {
    21         background-color: var(--bs-gray-100);
    22     }
    23
    24     .btn-primary {
    25         background-color: var(--theme-default) !important;
    26         border-color: var(--theme-default) !important;
    27     }
    28
    29     .btn-primary:hover {
    30         opacity: 0.9;
    31         background-color: var(--theme-default) !important;
    32         border-color: var(--theme-default) !important;
    33     }
    34
    35     .tab-content {
    36         border-left: 1px solid #eee;
    37         min-height: 400px;
    38     }
    39 </style>
    40 <div class="container-fluid">
    41     <div class="page-title">
    42         <div class="row">
    43             <div class="col-sm-6">
    44                 <h3>Services Page Settings</h3>
    45             </div>
    46             <div class="col-sm-6">
    47                 <ol class="breadcrumb">
    48                     <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid       fa-house"></i></a></li>
    49                     <li class="breadcrumb-item">Settings</li>
    50                     <li class="breadcrumb-item active">Profile Page Settings</li>
    51                 </ol>
    52             </div>
    53         </div>
    54     </div>
    55 </div>
    56
    57 <div class="container-fluid">
    58     <div class="row">
    59         <div class="col-sm-12">
    60             <div class="card">
    61                 <div class="card-header pb-0 card-no-border">
    62                     <h3>Manage Services Page Content</h3>
    63                     <p>Update content for the Services page, including the banner and statistics.</p>      
    64                 </div>
    65
    66                 <div class="card-body">
    67                     <form id="servicesSettingsForm" action="{{ route('admin.profilepage-settings.update') }}" 
       method="POST" enctype="multipart/form-data">
    68                         @csrf
    69                         <div class="row g-3">
    70                             <div class="col-md-3">
    71                                 <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist"       aria-orientation="vertical">
    72                                     <button class="nav-link active text-start mb-2"
       id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab"  
       aria-controls="v-pills-general" aria-selected="true">
    73                                         <i class="fa-solid fa-circle-info me-2"></i> General
    74                                     </button>
    75                                     <button class="nav-link text-start mb-2" id="v-pills-stats-tab"        
       data-bs-toggle="pill" data-bs-target="#v-pills-stats" type="button" role="tab"
       aria-controls="v-pills-stats" aria-selected="false">
    76                                         <i class="fa-solid fa-chart-simple me-2"></i> Statistics
    77                                     </button>
    78                                 </ul>
    79                             </div>
    80                             <div class="col-md-9 border-start">
    81                                 <div class="tab-content" id="v-pills-tabContent">
    82                                     @php
    83                                         $statsSettings = $settings->filter(fn($s) => Str::contains($s->key,       'stat'));
    84                                         $generalSettings = $settings->filter(fn($s) =>
       !Str::contains($s->key, 'stat'));
    85                                     @endphp
    86
    87                                     <!-- General Tab -->
    88                                     <div class="tab-pane fade show active p-3" id="v-pills-general"        
       role="tabpanel" aria-labelledby="v-pills-general-tab">
    89                                         <div class="row g-4">
    90                                             @foreach($generalSettings as $setting)
    91                                                 @include('admin.services-settings.partials.field',
       ['setting' => $setting])
    92                                             @endforeach
    93                                         </div>
    94                                     </div>
    95
    96                                     <!-- Stats Tab -->
    97                                     <div class="tab-pane fade p-3" id="v-pills-stats" role="tabpanel"
       aria-labelledby="v-pills-stats-tab">
    98                                         <div class="row g-4">
    99                                             @foreach($statsSettings as $setting)
   100                                                 @include('admin.services-settings.partials.field',
       ['setting' => $setting])
   101                                             @endforeach
   102                                         </div>
   103                                     </div>
   104                                 </div>
   105                             </div>
   106                         </div>
   107
   108                         <div class="card-footer text-end mt-4">
   109                             <button type="submit" id="saveSettingsBtn" class="btn btn-primary px-5">Save   
       All Settings</button>
   110                         </div>
   111                     </form>
   112                 </div>
   113             </div>
   114         </div>
   115     </div>
   116 </div>
   117 @endsection
   118
   119 @section('scripts')
   120 <script>
   121     $(document).ready(function() {
   122         // Function to activate tab based on hash
   123         function activateTabFromHash() {
   124             let hash = window.location.hash;
   125             if (hash) {
   126                 // Find button that targets this hash
   127                 let tabBtn = $(`button[data-bs-target="${hash}"]`);
   128                 if (tabBtn.length) {
   129                     tabBtn.trigger('click');
   130                 }
   131             }
   132         }
   133
   134         // Run on load
   135         activateTabFromHash();
   136
   137         // Run on hash change
   138         $(window).on('hashchange', function() {
   139             activateTabFromHash();
   140         });
   141
   142         $('#servicesSettingsForm').on('submit', function(e) {
   143             e.preventDefault();
   144
   145             let form = $(this);
   146             let btn = $('#saveSettingsBtn');
   147             let formData = new FormData(this);
   148
   149             // Disable button and show loading
   150             btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...'); 
   151
   152             $.ajax({
   153                 url: form.attr('action'),
   154                 type: 'POST',
   155                 data: formData,
   156                 processData: false,
   157                 contentType: false,
   158                 success: function(response) {
   159                     if (response.success) {
   160                         if (typeof showToast === 'function') {
   161                             showToast(response.message, 'success');
   162                         } else {
   163                             alert(response.message);
   164                         }
   165
   166                         // Reload after a short delay to allow toast to be seen
   167                         setTimeout(function() {
   168                             location.reload();
   169                         }, 1000);
   170                     }
   171                 },
   172                 error: function(xhr) {
   173                     let errorMsg = 'An error occurred while saving.';
   174                     if (xhr.responseJSON && xhr.responseJSON.message) {
   175                         errorMsg = xhr.responseJSON.message;
   176                     }
   177                     if (typeof showToast === 'function') {
   178                         showToast(errorMsg, 'error');
   179                     } else {
   180                         alert(errorMsg);
   181                     }
   182                 },
   183                 complete: function() {
   184                     btn.prop('disabled', false).text('Save All Settings');
   185                 }
   186             });
   187         });
   188
   189         // Image Preview only (Upload happens on form submit)
   190         $('.image-ajax-input').on('change', function() {
   191             const input = this;
   192             const key = $(this).data('key');
   193             const file = input.files[0];
   194
   195             if (file) {
   196                 // Immediate Preview
   197                 const reader = new FileReader();
   198                 reader.onload = function(e) {
   199                     $('.preview-' + key).attr('src', e.target.result).parent().removeClass('d-none');      
   200                 }
   201                 reader.readAsDataURL(file);
   202             }
   203         });
   204
   205         @if(session('success'))
   206         if (typeof showToast === 'function') {
   207             showToast("{{ session('success') }}", 'success');
   208         } else {
   209             alert("{{ session('success') }}");
   210         }
   211         @endif
   212     });