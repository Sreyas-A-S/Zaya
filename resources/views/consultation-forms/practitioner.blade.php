<form method="POST" action="{{ route('bookings.consultation-form.store', $booking->id) }}" class="consultation-form-root space-y-6">
    @csrf

    <div class="space-y-6">
        <div class="consultation-tab-controls">
            <div class="consultation-tabs" role="tablist">
                <button type="button" class="consultation-tab-button is-active" data-tab="assessment" aria-controls="tab-assessment" aria-selected="true">
                    <span class="consultation-tab-title">Assessment</span>
                    <span class="consultation-tab-subtitle">Concerns, Notes, Habits</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="plan" aria-controls="tab-plan" aria-selected="false">
                    <span class="consultation-tab-title">Treatment Plan</span>
                    <span class="consultation-tab-subtitle">Therapies, Follow-up</span>
                </button>
            </div>
        </div>

        <div class="consultation-tab-panels">
            <div data-tab-panel="assessment" class="consultation-tab-panel space-y-6 is-active" id="tab-assessment">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#F9FBFA] rounded-2xl border border-[#2E4B3D]/10 p-5 shadow-sm">
                        <h2 class="text-lg font-bold text-secondary mb-4">Primary Concern</h2>
                        <textarea name="primary_concern" rows="6" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm" placeholder="Symptoms, body type, main complaint">{{ data_get($payload, 'primary_concern') }}</textarea>
                    </div>
                    <div class="bg-[#F9FBFA] rounded-2xl border border-[#2E4B3D]/10 p-5 shadow-sm">
                        <h2 class="text-lg font-bold text-secondary mb-4">Assessment Notes</h2>
                        <textarea name="assessment_notes" rows="6" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm" placeholder="Body therapies, sensitivities, priorities">{{ data_get($payload, 'assessment_notes') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-[#2E4B3D]/10 p-5 shadow-sm">
                    <h2 class="text-lg font-bold text-secondary mb-4">Lifestyle & Habits</h2>
                    <textarea name="lifestyle_habits" rows="7" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm" placeholder="Diet, sleep, exercise, addictions, work routine">{{ data_get($payload, 'lifestyle_habits') }}</textarea>
                </div>
            </div>

            <div data-tab-panel="plan" class="consultation-tab-panel space-y-6" id="tab-plan">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#F9FBFA] rounded-2xl border border-[#2E4B3D]/10 p-5 shadow-sm">
                        <h2 class="text-lg font-bold text-secondary mb-4">Suggested Therapies</h2>
                        <textarea name="suggested_therapies" rows="8" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm" placeholder="Therapy name, type, duration, frequency">{{ data_get($payload, 'suggested_therapies') }}</textarea>
                    </div>
                    <div class="bg-[#F9FBFA] rounded-2xl border border-[#2E4B3D]/10 p-5 shadow-sm">
                        <h2 class="text-lg font-bold text-secondary mb-4">Follow-Up Plan</h2>
                        <textarea name="follow_up_plan" rows="8" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm" placeholder="Review dates, adjustments, expected outcomes">{{ data_get($payload, 'follow_up_plan') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-[#2E4B3D]/10 p-5 shadow-sm">
                    <h2 class="text-lg font-bold text-secondary mb-4">Consulting Practitioner</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input name="consultant[name]" value="{{ data_get($payload, 'consultant.name', $user->name ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm" placeholder="Name">
                        <input name="consultant[speciality]" value="{{ data_get($payload, 'consultant.speciality') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm" placeholder="Speciality">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 pt-8 mt-4 border-t border-gray-100">
            <div class="flex gap-3">
                <button type="button" id="consultation-prev-tab" class="hidden px-6 py-3 rounded-full border border-gray-200 text-sm font-bold text-secondary hover:bg-gray-50 transition-all">
                    <i class="ri-arrow-left-line mr-2"></i> Previous
                </button>
                <button type="button" id="consultation-next-tab" class="px-6 py-3 rounded-full bg-secondary text-white text-sm font-bold hover:bg-primary transition-all">
                    Next Section <i class="ri-arrow-right-line ml-2"></i>
                </button>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-8 py-3 rounded-full bg-secondary text-white text-sm font-bold hover:shadow-lg transition-all">
                    <i class="ri-save-line mr-2"></i> Save Form
                </button>
                <a href="{{ route('bookings.index') }}" class="px-6 py-3 rounded-full border border-gray-200 text-sm font-bold text-gray-400 hover:text-gray-600 transition-all">
                    Back
                </a>
            </div>
        </div>
    </div>
</form>
