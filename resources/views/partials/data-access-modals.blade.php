<!-- Data Access Request Modal -->
<div id="data-access-request-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeDataAccessRequestModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-8 text-center">
                    <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ri-shield-user-line text-4xl text-orange-500"></i>
                    </div>
                    <h3 class="text-xl font-black text-secondary tracking-tight mb-4">Authorize Data Access</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-8">
                        This will send a secure OTP to the client to authorize your access to their health journey and historical records.
                    </p>
                    <div class="flex flex-col gap-3">
                        <button type="button" id="data-access-confirm-btn" onclick="executeDataAccessRequest()" class="w-full py-4 bg-secondary text-white rounded-2xl font-black text-sm hover:bg-primary transition-all shadow-lg uppercase tracking-widest">
                            Proceed & Send OTP
                        </button>
                        <button type="button" onclick="closeDataAccessRequestModal()" class="w-full py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold text-sm hover:bg-gray-100 transition-all uppercase tracking-widest">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Access OTP Modal -->
<div id="data-access-otp-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeDataAccessOTPModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-8 text-center">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ri-key-2-line text-4xl text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-black text-secondary tracking-tight mb-2">Verify Access OTP</h3>
                    <p id="data-access-otp-message" class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-6">Enter the 6-digit code</p>
                    
                    <div class="mb-8">
                        <input type="text" id="data-access-otp-input" maxlength="6" placeholder="000000" 
                            class="w-full text-center text-3xl font-black tracking-[0.5em] py-4 rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 transition-all bg-gray-50/50">
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="button" id="data-access-verify-btn" onclick="executeDataAccessVerify()" class="w-full py-4 bg-secondary text-white rounded-2xl font-black text-sm hover:bg-primary transition-all shadow-lg uppercase tracking-widest">
                            Verify & Grant Access
                        </button>
                        <button type="button" onclick="closeDataAccessOTPModal()" class="w-full py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold text-sm hover:bg-gray-100 transition-all uppercase tracking-widest">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .time-slots-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    .time-slot {
        padding: 12px 6px;
        text-align: center;
        font-size: 10px;
        color: #2E4B3D;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        border: 2px solid #f8fafc;
        font-weight: 900;
        background: #ffffff;
        letter-spacing: 0.05em;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .time-slot:hover:not(.booked):not(.selected) {
        background-color: #f8fafc;
        border-color: #2E4B3D;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .time-slot.selected {
        background-color: #2E4B3D;
        color: #fff;
        border-color: #2E4B3D;
        box-shadow: 0 10px 15px -3px rgba(46, 75, 61, 0.25);
        transform: translateY(-2px);
    }
    .time-slot.booked {
        background-color: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #f1f5f9 !important;
        cursor: not-allowed !important;
        font-weight: 600;
        box-shadow: none;
    }
</style>

<script>
    let currentDataAccessClientId = null;
    let dataAccessOnSuccess = null;

    function openDataAccessRequestModal(clientId, callback = null) {
        currentDataAccessClientId = clientId;
        dataAccessOnSuccess = callback;
        document.getElementById('data-access-request-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDataAccessRequestModal() {
        document.getElementById('data-access-request-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function closeDataAccessOTPModal() {
        document.getElementById('data-access-otp-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    async function executeDataAccessRequest() {
        const btn = document.getElementById('data-access-confirm-btn');
        if (!currentDataAccessClientId || !btn) return;

        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerText = 'Sending OTP...';

        try {
            const response = await fetch("{{ route('data-access.request') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ client_id: currentDataAccessClientId })
            });

            const data = await response.json();
            if (response.ok) {
                closeDataAccessRequestModal();
                document.getElementById('data-access-otp-input').value = '';
                document.getElementById('data-access-otp-message').innerText = data.success || 'Enter the 6-digit code';
                document.getElementById('data-access-otp-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                alert(data.error || 'Failed to send OTP.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
    }

    async function executeDataAccessVerify() {
        const otpInput = document.getElementById('data-access-otp-input');
        const btn = document.getElementById('data-access-verify-btn');
        if (!currentDataAccessClientId || !otpInput || !btn) return;

        const otp = otpInput.value.trim();
        if (otp.length !== 6) {
            alert('Please enter a valid 6-digit OTP.');
            return;
        }

        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerText = 'Verifying...';

        try {
            const response = await fetch("{{ route('data-access.verify') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ client_id: currentDataAccessClientId, otp: otp })
            });

            const data = await response.json();
            if (response.ok) {
                if (window.showZayaToast) showZayaToast('Access Granted!', 'Success');
                closeDataAccessOTPModal();
                
                if (typeof dataAccessOnSuccess === 'function') {
                    dataAccessOnSuccess(currentDataAccessClientId);
                } else {
                    location.reload();
                }
            } else {
                alert(data.error || 'Invalid OTP.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
    }
</script>
