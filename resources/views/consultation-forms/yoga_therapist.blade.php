@php
    $oldOrPayload = fn (string $key, $default = null) => old($key, data_get($payload, $key, $default));
    $isChecked = fn ($values, string $option) => in_array($option, is_array($values) ? $values : (blank($values) ? [] : [$values]), true);

    $sessionProgress = old('session_progress_report', data_get($payload, 'session_progress_report', []));
    $sessionProgress = is_array($sessionProgress) ? array_values($sessionProgress) : [];
    if (count($sessionProgress) < 2) {
        $sessionProgress[] = ['date' => '', 'session_no' => '', 'issues_complaints' => '', 'practices_effect' => ''];
    }

    $mitahara = old('section_10_treatment_plan.mitahara', data_get($payload, 'section_10_treatment_plan.mitahara', []));
    $mitahara = is_array($mitahara) ? array_values($mitahara) : [];
    if (count($mitahara) < 1) {
        $mitahara[] = ['time' => '', 'food_content' => ''];
    }
@endphp

<form id="yoga-consultation-form" method="POST" action="{{ route('bookings.consultation-form.store', $booking->id) }}" class="consultation-form-root space-y-6">
    @csrf

    <div class="space-y-6">
        <div class="consultation-tab-controls">
            <div class="consultation-tabs" role="tablist">
                <button type="button" class="consultation-tab-button is-active" data-tab="patient-info" aria-controls="tab-patient-info" aria-selected="true">
                    <span class="consultation-tab-title">Patient Info</span>
                    <span class="consultation-tab-subtitle">ID, Consent, Concerns</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="examination" aria-controls="tab-examination" aria-selected="false">
                    <span class="consultation-tab-title">Examination</span>
                    <span class="consultation-tab-subtitle">Physical, Vital, Systemic</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="psychological" aria-controls="tab-psychological" aria-selected="false">
                    <span class="consultation-tab-title">Psychological</span>
                    <span class="consultation-tab-subtitle">Mental, Emotional, Habits</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="treatment" aria-controls="tab-treatment" aria-selected="false">
                    <span class="consultation-tab-title">Treatment Plan</span>
                    <span class="consultation-tab-subtitle">Yoga, Diet, Follow-up</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="progress" aria-controls="tab-progress" aria-selected="false">
                    <span class="consultation-tab-title">Progress</span>
                    <span class="consultation-tab-subtitle">Session Reports</span>
                </button>
            </div>
        </div>

        <div class="consultation-tab-panels">
            <!-- Tab 1: Patient Info -->
            <div data-tab-panel="patient-info" class="consultation-tab-panel space-y-6 is-active" id="tab-patient-info">
                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">1. Patient Identification</h2>
                        <div class="grid md:grid-cols-3 gap-3">
                            <input class="border rounded px-3 py-2" name="section_1_patient_identification[uhid_case_id]" value="{{ $oldOrPayload('section_1_patient_identification.uhid_case_id') }}" placeholder="UHID / Case ID">
                            <input class="border rounded px-3 py-2" name="section_1_patient_identification[name]" value="{{ $oldOrPayload('section_1_patient_identification.name') }}" placeholder="Name">
                            <input class="border rounded px-3 py-2" name="section_1_patient_identification[age_gender]" value="{{ $oldOrPayload('section_1_patient_identification.age_gender') }}" placeholder="Age / Gender">
                            <input class="border rounded px-3 py-2" name="section_1_patient_identification[occupation]" value="{{ $oldOrPayload('section_1_patient_identification.occupation') }}" placeholder="Occupation">
                            <input class="border rounded px-3 py-2" name="section_1_patient_identification[marital_status]" value="{{ $oldOrPayload('section_1_patient_identification.marital_status') }}" placeholder="Marital status">
                            <input class="border rounded px-3 py-2" name="section_1_patient_identification[contact_details]" value="{{ $oldOrPayload('section_1_patient_identification.contact_details') }}" placeholder="Contact details">
                            <textarea class="border rounded px-3 py-2 md:col-span-2" rows="2" name="section_1_patient_identification[address]" placeholder="Address">{{ $oldOrPayload('section_1_patient_identification.address') }}</textarea>
                            <input class="border rounded px-3 py-2" name="section_1_patient_identification[emergency_contact]" value="{{ $oldOrPayload('section_1_patient_identification.emergency_contact') }}" placeholder="Emergency contact">
                            <input class="border rounded px-3 py-2" name="section_1_patient_identification[referring_department]" value="{{ $oldOrPayload('section_1_patient_identification.referring_department') }}" placeholder="Referring department">
                            <input class="border rounded px-3 py-2" type="date" name="section_1_patient_identification[date_of_registration]" value="{{ $oldOrPayload('section_1_patient_identification.date_of_registration') }}">
                            <input class="border rounded px-3 py-2" type="date" name="section_1_patient_identification[date_of_first_consultation]" value="{{ $oldOrPayload('section_1_patient_identification.date_of_first_consultation') }}">
                            <textarea class="border rounded px-3 py-2" rows="2" name="section_1_patient_identification[medical_diagnosis]" placeholder="Medical diagnosis">{{ $oldOrPayload('section_1_patient_identification.medical_diagnosis') }}</textarea>
                            <textarea class="border rounded px-3 py-2" rows="2" name="section_1_patient_identification[current_medication]" placeholder="Current medication">{{ $oldOrPayload('section_1_patient_identification.current_medication') }}</textarea>
                            <textarea class="border rounded px-3 py-2" rows="2" name="section_1_patient_identification[previous_therapy_counselling]" placeholder="Previous therapy / counselling">{{ $oldOrPayload('section_1_patient_identification.previous_therapy_counselling') }}</textarea>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">2. Informed Consent</h2>
                        <div class="flex flex-wrap gap-4">
                            @foreach(['informed_consent_obtained'=>'Consent obtained','scope_limitations_explained'=>'Scope explained','confidentiality_explained'=>'Confidentiality explained','referral_protocol_explained'=>'Referral protocol explained'] as $key => $label)
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="section_2_informed_consent[{{ $key }}]" value="1" @checked((bool)$oldOrPayload("section_2_informed_consent.{$key}"))>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        <div class="grid md:grid-cols-3 gap-3">
                            <input class="border rounded px-3 py-2" name="section_2_informed_consent[patient_signature]" value="{{ $oldOrPayload('section_2_informed_consent.patient_signature') }}" placeholder="Patient signature">
                            <input class="border rounded px-3 py-2" name="section_2_informed_consent[practitioner_signature]" value="{{ $oldOrPayload('section_2_informed_consent.practitioner_signature') }}" placeholder="Practitioner signature">
                            <input class="border rounded px-3 py-2" type="date" name="section_2_informed_consent[date]" value="{{ $oldOrPayload('section_2_informed_consent.date') }}">
                        </div>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">3. Presenting Concern</h2>
                        <textarea class="border rounded px-3 py-2 w-full" rows="3" name="section_3_presenting_concern[chief_complaint]" placeholder="Chief complaint">{{ $oldOrPayload('section_3_presenting_concern.chief_complaint') }}</textarea>
                        <div class="grid md:grid-cols-3 gap-3">
                            <div>
                                <p class="text-sm font-semibold">Onset</p>
                                @foreach(['sudden','gradual','cannot_recall'] as $option)
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="section_3_presenting_concern[onset][{{ $option }}]" value="1" @checked((bool)$oldOrPayload("section_3_presenting_concern.onset.{$option}"))>
                                        {{ \Illuminate\Support\Str::headline($option) }}
                                    </label>
                                @endforeach
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Duration</p>
                                @foreach(['less_than_3_months'=>'<3 months','3_to_12_months'=>'3-12 months','more_than_1_year'=>'>1 year'] as $key => $label)
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="section_3_presenting_concern[duration][{{ $key }}]" value="1" @checked((bool)$oldOrPayload("section_3_presenting_concern.duration.{$key}"))>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            <div>
                                <label class="block text-sm font-semibold">Stress level score (0-10)</label>
                                <input class="border rounded px-3 py-2" type="number" min="0" max="10" name="section_3_presenting_concern[stress_level_score]" value="{{ $oldOrPayload('section_3_presenting_concern.stress_level_score') }}">
                            </div>
                        </div>
                        <textarea class="border rounded px-3 py-2 w-full" rows="2" name="section_3_presenting_concern[root_cause_trigger]" placeholder="Root cause / trigger">{{ $oldOrPayload('section_3_presenting_concern.root_cause_trigger') }}</textarea>
                    </section>
                </div>

                <!-- Tab 2: Examination -->
                <div data-tab-panel="examination" class="consultation-tab-panel space-y-6" id="tab-examination">
                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">4. Physical & Vital Signs</h2>
                        <div class="grid md:grid-cols-3 gap-3">
                            <input class="border rounded px-3 py-2" name="section_4_physical_vital_signs[height]" value="{{ $oldOrPayload('section_4_physical_vital_signs.height') }}" placeholder="Height">
                            <input class="border rounded px-3 py-2" name="section_4_physical_vital_signs[weight]" value="{{ $oldOrPayload('section_4_physical_vital_signs.weight') }}" placeholder="Weight">
                            <input class="border rounded px-3 py-2" name="section_4_physical_vital_signs[bmi]" value="{{ $oldOrPayload('section_4_physical_vital_signs.bmi') }}" placeholder="BMI">
                            <input class="border rounded px-3 py-2" name="section_4_physical_vital_signs[bp]" value="{{ $oldOrPayload('section_4_physical_vital_signs.bp') }}" placeholder="BP">
                            <input class="border rounded px-3 py-2" name="section_4_physical_vital_signs[pulse]" value="{{ $oldOrPayload('section_4_physical_vital_signs.pulse') }}" placeholder="Pulse">
                            <input class="border rounded px-3 py-2" name="section_4_physical_vital_signs[respiration]" value="{{ $oldOrPayload('section_4_physical_vital_signs.respiration') }}" placeholder="Respiration">
                            <textarea class="border rounded px-3 py-2 md:col-span-3" rows="2" name="section_4_physical_vital_signs[appetite_notes]" placeholder="Appetite notes">{{ $oldOrPayload('section_4_physical_vital_signs.appetite_notes') }}</textarea>
                            <textarea class="border rounded px-3 py-2 md:col-span-3" rows="2" name="section_4_physical_vital_signs[sleep_notes]" placeholder="Sleep notes">{{ $oldOrPayload('section_4_physical_vital_signs.sleep_notes') }}</textarea>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">5. Systemic Observation</h2>
                        <div class="grid md:grid-cols-2 gap-3">
                            @foreach(['respiratory'=>'Respiratory','circulatory'=>'Circulatory','digestive'=>'Digestive','nervous'=>'Nervous','musculoskeletal'=>'Musculoskeletal','genitourinary'=>'Genitourinary','others'=>'Others'] as $key => $label)
                                <textarea class="border rounded px-3 py-2" rows="2" name="section_5_systemic_observation[{{ $key }}]" placeholder="{{ $label }} observation">{{ $oldOrPayload("section_5_systemic_observation.{$key}") }}</textarea>
                            @endforeach
                        </div>
                    </section>
                </div>

                <!-- Tab 3: Psychological -->
                <div data-tab-panel="psychological" class="consultation-tab-panel space-y-6" id="tab-psychological">
                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">6. Mental / Emotional Observation</h2>
                        <div class="grid md:grid-cols-2 gap-3">
                            <textarea class="border rounded px-3 py-2" rows="2" name="section_6_mental_emotional_observation[nature_of_work]" placeholder="Nature of work / lifestyle">{{ $oldOrPayload('section_6_mental_emotional_observation.nature_of_work') }}</textarea>
                            <textarea class="border rounded px-3 py-2" rows="2" name="section_6_mental_emotional_observation[family_dynamics]" placeholder="Family dynamics">{{ $oldOrPayload('section_6_mental_emotional_observation.family_dynamics') }}</textarea>
                            <textarea class="border rounded px-3 py-2" rows="2" name="section_6_mental_emotional_observation[major_stresses]" placeholder="Major life stresses">{{ $oldOrPayload('section_6_mental_emotional_observation.major_stresses') }}</textarea>
                            <textarea class="border rounded px-3 py-2" rows="2" name="section_6_mental_emotional_observation[mental_disposition]" placeholder="Mental disposition (Anxiety, Depression, etc.)">{{ $oldOrPayload('section_6_mental_emotional_observation.mental_disposition') }}</textarea>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">7. Personal History / Lifestyle</h2>
                        <div class="grid md:grid-cols-2 gap-3">
                            @foreach(['bowel'=>'Bowel','micturition'=>'Micturition','diet_appetite'=>'Diet / Appetite','sleep'=>'Sleep','addictions_habits'=>'Addictions / Habits'] as $key => $label)
                                <textarea class="border rounded px-3 py-2" rows="2" name="section_7_personal_history_lifestyle[{{ $key }}]" placeholder="{{ $label }} details">{{ $oldOrPayload("section_7_personal_history_lifestyle.{$key}") }}</textarea>
                            @endforeach
                        </div>
                    </section>
                </div>

                <!-- Tab 4: Treatment -->
                <div data-tab-panel="treatment" class="consultation-tab-panel space-y-6" id="tab-treatment">
                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">8. Evaluation / Goal Setting</h2>
                        <textarea class="border rounded px-3 py-2 w-full" rows="3" name="section_8_evaluation_goal_setting[goals_of_therapy]" placeholder="Goals of therapy">{{ $oldOrPayload('section_8_evaluation_goal_setting.goals_of_therapy') }}</textarea>
                        <textarea class="border rounded px-3 py-2 w-full" rows="2" name="section_8_evaluation_goal_setting[practitioner_remarks]" placeholder="Practitioner remarks">{{ $oldOrPayload('section_8_evaluation_goal_setting.practitioner_remarks') }}</textarea>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">9. Progress Monitoring</h2>
                        <textarea class="border rounded px-3 py-2 w-full" rows="2" name="section_9_progress_monitoring[monitoring_parameter]" placeholder="Parameter for monitoring progress">{{ $oldOrPayload('section_9_progress_monitoring.monitoring_parameter') }}</textarea>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">10. Treatment Plan (Mitahara)</h2>
                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="text-left p-2">Time</th>
                                    <th class="text-left p-2">Food Content</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mitahara as $index => $row)
                                <tr>
                                    <td class="p-2"><input class="border rounded px-3 py-2 w-full" type="time" name="section_10_treatment_plan[mitahara][{{ $index }}][time]" value="{{ $row['time'] }}"></td>
                                    <td class="p-2"><input class="border rounded px-3 py-2 w-full" name="section_10_treatment_plan[mitahara][{{ $index }}][food_content]" value="{{ $row['food_content'] }}" placeholder="Food content"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">11. Prescription / Recommendations</h2>
                        <textarea class="border rounded px-3 py-2 w-full" rows="4" name="section_11_prescription_recommendations[recommendations]" placeholder="Yoga practices, lifestyle changes, etc.">{{ $oldOrPayload('section_11_prescription_recommendations.recommendations') }}</textarea>
                        <div class="grid md:grid-cols-2 gap-3">
                            <input class="border rounded px-3 py-2" name="section_11_prescription_recommendations[prescribing_consultant]" value="{{ $oldOrPayload('section_11_prescription_recommendations.prescribing_consultant') }}" placeholder="Prescribing consultant">
                            <input class="border rounded px-3 py-2" type="date" name="section_11_prescription_recommendations[date]" value="{{ $oldOrPayload('section_11_prescription_recommendations.date') }}">
                        </div>
                    </section>
                </div>

                <!-- Tab 5: Progress -->
                <div data-tab-panel="progress" class="consultation-tab-panel space-y-6" id="tab-progress">
                    <section data-repeat-section="session_progress_report" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
                        <h2 class="text-lg font-semibold">Session Progress Report</h2>
                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="text-left p-2">Date</th>
                                    <th class="text-left p-2">Session No.</th>
                                    <th class="text-left p-2">Issues / Complaints</th>
                                    <th class="text-left p-2">Practices & Effect</th>
                                    <th class="text-left p-2"></th>
                                </tr>
                            </thead>
                            <tbody data-repeat-body="session_progress_report">
                                @foreach($sessionProgress as $index => $row)
                                <tr data-repeat-row data-row-index="{{ $index }}">
                                    <td class="p-2"><input class="border rounded px-3 py-2 w-full" type="date" name="session_progress_report[{{ $index }}][date]" value="{{ $row['date'] }}"></td>
                                    <td class="p-2"><input class="border rounded px-3 py-2 w-full" type="number" name="session_progress_report[{{ $index }}][session_no]" value="{{ $row['session_no'] }}"></td>
                                    <td class="p-2"><textarea class="border rounded px-3 py-2 w-full" rows="2" name="session_progress_report[{{ $index }}][issues_complaints]">{{ $row['issues_complaints'] }}</textarea></td>
                                    <td class="p-2"><textarea class="border rounded px-3 py-2 w-full" rows="2" name="session_progress_report[{{ $index }}][practices_effect]">{{ $row['practices_effect'] }}</textarea></td>
                                    <td class="p-2"><button type="button" class="text-red-600 text-xs font-bold" data-repeat-remove="session_progress_report">Remove</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" data-repeat-add="session_progress_report" class="text-sm font-semibold text-secondary hover:text-primary">
                            <i class="ri-add-line"></i> Add session report
                        </button>
                        <template data-repeat-template="session_progress_report">
                            <tr data-repeat-row data-row-index="__INDEX__">
                                <td class="p-2"><input class="border rounded px-3 py-2 w-full" type="date" name="session_progress_report[__INDEX__][date]"></td>
                                <td class="p-2"><input class="border rounded px-3 py-2 w-full" type="number" name="session_progress_report[__INDEX__][session_no]"></td>
                                <td class="p-2"><textarea class="border rounded px-3 py-2 w-full" rows="2" name="session_progress_report[__INDEX__][issues_complaints]"></textarea></td>
                                <td class="p-2"><textarea class="border rounded px-3 py-2 w-full" rows="2" name="session_progress_report[__INDEX__][practices_effect]"></textarea></td>
                                <td class="p-2"><button type="button" class="text-red-600 text-xs font-bold" data-repeat-remove="session_progress_report">Remove</button></td>
                            </tr>
                        </template>
                    </section>
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
</div>

@push('scripts')
<script>
(() => {
    const form = document.getElementById('yoga-consultation-form');
    if (!form) return;

    const counters = {};
    form.querySelectorAll('[data-repeat-section]').forEach((section) => {
        const key = section.getAttribute('data-repeat-section');
        const rows = section.querySelectorAll('[data-repeat-row]');
        counters[key] = rows.length;
    });

    const addRow = (sectionKey) => {
        const template = form.querySelector(`template[data-repeat-template="${sectionKey}"]`);
        const body = form.querySelector(`[data-repeat-body="${sectionKey}"]`);
        if (!template || !body) return;

        const index = counters[sectionKey] ?? 0;
        counters[sectionKey] = index + 1;
        const html = template.innerHTML.replace(/__INDEX__/g, index);
        body.insertAdjacentHTML('beforeend', html);
    };

    form.addEventListener('click', (event) => {
        const addButton = event.target.closest('[data-repeat-add]');
        if (addButton) {
            event.preventDefault();
            addRow(addButton.getAttribute('data-repeat-add'));
            return;
        }

        const removeButton = event.target.closest('[data-repeat-remove]');
        if (removeButton) {
            event.preventDefault();
            const row = removeButton.closest('[data-repeat-row]');
            if (row) row.remove();
        }
    });
})();
</script>
@endpush
