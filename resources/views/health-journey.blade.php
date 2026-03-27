@extends('layouts.client')

@section('title', 'Health Journey')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-secondary">My Health Journey</h1>
            <p class="text-gray-500 mt-1">Manage your medical records and track your consultation history.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Clinical Documents -->
        <div class="lg:col-span-12 space-y-8">
            <div id="section-clinical" class="bg-white rounded-[2rem] p-6 md:p-8 border border-[#2E4B3D]/12 shadow-sm">
                <h2 class="text-2xl font-bold text-secondary mb-6 flex items-center gap-3">
                    <i class="ri-file-list-3-line text-[#FABD4D]"></i> Clinical Document Portal
                </h2>

                <!-- Upload Area -->
                <form id="upload-form" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="document-input" name="document" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    <div id="drop-zone"
                        class="border-2 border-dashed border-[#8FC0A8] rounded-[2rem] p-10 text-center bg-gray-50/50 mb-8 cursor-pointer hover:bg-white hover:border-secondary transition-all group">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ri-upload-cloud-2-line text-2xl text-secondary"></i>
                        </div>
                        <p class="text-lg font-bold text-secondary mb-1">Drag and Drop files here</p>
                        <p class="text-sm text-gray-500 mb-6">Upload X-Rays, MRIs, Blood tests and other clinical documents</p>
                        <button type="button" id="client_panel_upload_btn"
                            class="inline-flex items-center justify-center px-8 py-3 bg-secondary text-white rounded-full text-sm font-bold hover:bg-primary transition-all shadow-lg shadow-secondary/20">
                            Select Files
                        </button>
                        <p class="text-xs text-gray-400 mt-4 uppercase tracking-widest font-bold">JPG, PNG, PDF (Max 20MB)</p>
                    </div>
                </form>

                <!-- Documents Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4" id="documents-wrapper">
                    @forelse($clinicalDocuments as $doc)
                    <div class="group bg-white p-4 rounded-2xl relative flex flex-col items-center justify-center border border-gray-100 hover:border-secondary/20 hover:shadow-xl hover:shadow-gray-200/40 transition-all" id="doc-{{ $doc->id }}">
                        <button onclick="deleteDocument({{ $doc->id }})"
                            class="absolute top-2 right-2 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-red-500 hover:text-white transition-all z-10">
                            <i class="ri-delete-bin-line text-xs"></i>
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
                            <div class="w-12 h-12 {{ $bgColor }} {{ $textColor }} flex items-center justify-center rounded-xl mb-3 group-hover:scale-110 transition-transform">
                                <i class="{{ $icon }} text-2xl"></i>
                            </div>
                            <p class="text-[11px] font-bold text-gray-800 truncate w-full text-center px-2" title="{{ $doc->file_name }}">
                                {{ $doc->file_name }}</p>
                            <p class="text-[9px] text-gray-400 mt-1 uppercase font-black">{{ $doc->file_type }}</p>
                        </a>
                    </div>
                    @empty
                    <div id="no-docs-msg" class="col-span-full py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-folder-open-line text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-400 font-medium">No documents uploaded yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Consultation History -->
            <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-[#2E4B3D]/12 shadow-sm">
                <h2 class="text-2xl font-bold text-secondary mb-6 flex items-center gap-3">
                    <i class="ri-history-line text-[#FABD4D]"></i> Consultation History
                </h2>

                <div class="space-y-4">
                    @forelse($consultations as $consultation)
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-5 rounded-2xl border border-gray-50 hover:border-secondary/10 hover:bg-gray-50/30 transition-all gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-white border border-gray-100 flex-shrink-0 overflow-hidden">
                                <img src="{{ $consultation->practitioner->profile_photo_path ? asset('storage/' . $consultation->practitioner->profile_photo_path) : asset('frontend/assets/profile-dummy-img.png') }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-secondary">{{ $consultation->practitioner->user->name ?? 'Practitioner' }}</h3>
                                <p class="text-xs text-gray-500">{{ $consultation->booking_date->format('M d, Y') }} • {{ $consultation->booking_time }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <a href="{{ route('bookings.consultation-form.show', $consultation->id) }}" 
                               class="px-5 py-2.5 rounded-full bg-white border border-gray-200 text-secondary text-xs font-bold hover:border-secondary hover:text-secondary transition-all flex items-center gap-2">
                                <i class="ri-file-list-3-line"></i> View Consultation Details
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-stethoscope-line text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-400 font-medium">No consultation records found yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals from Dashboard -->
@if($user->role === 'client' || $user->role === 'patient')
<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-[100002] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-[32px] p-8 md:p-10 max-w-[450px] w-[90%] text-center shadow-[0_20px_50px_rgba(0,0,0,0.1)] transform transition-all duration-300 scale-90">
        <button onclick="closeDeleteModal()" class="absolute top-6 right-8 text-gray-300 hover:text-gray-500 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>
        <div class="mb-6">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-delete-bin-line text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-secondary mb-2">{{ __('Delete Document?') }}</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                {{ __('Are you sure you want to delete this document? This action cannot be undone.') }}
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
document.addEventListener('DOMContentLoaded', function() {
    const docInput = document.getElementById('document-input');
    const uploadBtn = document.getElementById('client_panel_upload_btn');
    const dropZone = document.getElementById('drop-zone');

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
            dropZone.classList.add('bg-gray-100', 'border-secondary');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('bg-gray-100', 'border-secondary');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('bg-gray-100', 'border-secondary');
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
        const div = document.createElement('div');
        div.className = 'group bg-white p-4 rounded-2xl relative flex flex-col items-center justify-center border border-gray-100 hover:border-secondary/20 hover:shadow-xl transition-all';
        div.id = `doc-${doc.id}`;

        let bgColor = 'bg-blue-50', textColor = 'text-blue-500', icon = 'ri-file-text-fill';
        const ext = doc.file_type.toLowerCase();
        if (ext === 'pdf') { bgColor = 'bg-red-50'; textColor = 'text-red-500'; icon = 'ri-file-pdf-fill'; }
        else if (['jpg', 'jpeg', 'png'].includes(ext)) { bgColor = 'bg-green-50'; textColor = 'text-green-500'; icon = 'ri-image-fill'; }

        div.innerHTML = `
            <button onclick="deleteDocument(${doc.id})" class="absolute top-2 right-2 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all z-10"><i class="ri-delete-bin-line text-xs"></i></button>
            <a href="${url}" target="_blank" class="flex flex-col items-center justify-center w-full">
                <div class="w-12 h-12 ${bgColor} ${textColor} flex items-center justify-center rounded-xl mb-3"><i class="${icon} text-2xl"></i></div>
                <p class="text-[11px] font-bold text-gray-800 truncate w-full text-center px-2">${doc.file_name}</p>
                <p class="text-[9px] text-gray-400 mt-1 uppercase font-black">${doc.file_type}</p>
            </a>`;
        wrapper.prepend(div);
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
</script>
@endsection
