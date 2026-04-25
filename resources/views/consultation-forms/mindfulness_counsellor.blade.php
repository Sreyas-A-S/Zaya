@php
    $oldOrPayload = fn (string $key, $default = null) => old($key, data_get($payload, $key, $default));
    $isChecked = fn ($values, string $option) => in_array($option, is_array($values) ? $values : (blank($values) ? [] : [$values]), true);
@endphp

<form id="mindfulness-counsellor-consultation-form" method="POST" action="{{ route('bookings.consultation-form.store', $booking->id) }}" class="consultation-form-root space-y-6">
    @csrf
    <input type="hidden" name="form_id" value="{{ $existingForm->id ?? '' }}">
    <input type="hidden" name="form_title" value="{{ $existingForm->title ?? '' }}">

    <div class="space-y-6">
        <div class="consultation-tab-controls">
            <div class="consultation-tabs" role="tablist">
                <button type="button" class="consultation-tab-button is-active" data-tab="intake" aria-controls="tab-intake" aria-selected="true">
                    <span class="consultation-tab-title">Intake & Consent</span>
                    <span class="consultation-tab-subtitle">Identification, Medical</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="concern" aria-controls="tab-concern" aria-selected="false">
                    <span class="consultation-tab-title">Presenting Concern</span>
                    <span class="consultation-tab-subtitle">Chief complaint, Stress</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="antarayas" aria-controls="tab-antarayas" aria-selected="false">
                    <span class="consultation-tab-title">Antarayas</span>
                    <span class="consultation-tab-subtitle">Obstacles Assessment</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="panchakosha" aria-controls="tab-panchakosha" aria-selected="false">
                    <span class="consultation-tab-title">Panchakosha</span>
                    <span class="consultation-tab-subtitle">Layers Assessment</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="vritti" aria-controls="tab-vritti" aria-selected="false">
                    <span class="consultation-tab-title">Vritti</span>
                    <span class="consultation-tab-subtitle">Mental Modifications</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="treatment" aria-controls="tab-treatment" aria-selected="false">
                    <span class="consultation-tab-title">Treatment Plan</span>
                    <span class="consultation-tab-subtitle">Goals, Protocols</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="progress" aria-controls="tab-progress" aria-selected="false">
                    <span class="consultation-tab-title">Progress</span>
                    <span class="consultation-tab-subtitle">SOAP, Outcome</span>
                </button>
            </div>
        </div>

        <div class="consultation-tab-panels">
            <!-- Part 1: Intake & Consent -->
            <div data-tab-panel="intake" class="consultation-tab-panel space-y-6 is-active" id="tab-intake">
                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 1: PATIENT IDENTIFICATION</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">UHID / Case ID</label>
                            <input type="text" name="identification[uhid_case_id]" value="{{ $oldOrPayload('identification.uhid_case_id') }}" @readonly(!$canEdit)>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Occupation</label>
                            <input type="text" name="identification[occupation]" value="{{ $oldOrPayload('identification.occupation') }}" @readonly(!$canEdit)>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Marital Status</label>
                            <input type="text" name="identification[marital_status]" value="{{ $oldOrPayload('identification.marital_status') }}" @readonly(!$canEdit)>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Emergency Contact</label>
                            <input type="text" name="identification[emergency_contact]" value="{{ $oldOrPayload('identification.emergency_contact') }}" @readonly(!$canEdit)>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Referring Department</label>
                            <input type="text" name="identification[referring_department]" value="{{ $oldOrPayload('identification.referring_department') }}" @readonly(!$canEdit)>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Date of Registration</label>
                            <input type="date" name="identification[date_of_registration]" value="{{ $oldOrPayload('identification.date_of_registration') }}" @readonly(!$canEdit)>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Medical Diagnosis (if any)</label>
                            <textarea name="identification[medical_diagnosis_if_any]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('identification.medical_diagnosis_if_any') }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Current Medication</label>
                            <textarea name="identification[current_medication]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('identification.current_medication') }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Previous Therapy / Counselling</label>
                            <textarea name="identification[previous_therapy_counselling]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('identification.previous_therapy_counselling') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 2: INFORMED CONSENT</h2>
                    <div class="space-y-2">
                        @foreach(['Informed consent obtained', 'Scope & limitations explained', 'Confidentiality explained', 'Referral protocol explained'] as $option)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="consent[consent_items][]" value="{{ $option }}" {{ $isChecked($oldOrPayload('consent.consent_items', []), $option) ? 'checked' : '' }} @disabled(!$canEdit)>
                            <span class="text-sm">{{ $option }}</span>
                        </label>
                        @endforeach
                    </div>
                </section>
                
                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 4.1: MEDICAL HISTORY</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Co-morbidities (Hypertension, obesity, cardiac, diabetes, thyroid, etc.)</label>
                            <textarea name="medical[co_morbidities]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('medical.co_morbidities') }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Psychiatric history (personal & family)</label>
                            <textarea name="medical[psychiatric_history]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('medical.psychiatric_history') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Sleep pattern</label>
                                <textarea name="medical[sleep_pattern]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('medical.sleep_pattern') }}</textarea>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Appetite</label>
                                <textarea name="medical[appetite]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('medical.appetite') }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Part 2: Presenting Concern -->
            <div data-tab-panel="concern" class="consultation-tab-panel space-y-6" id="tab-concern">
                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 3: PRESENTING CONCERN</h2>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Chief Complaint (In patient’s own words)</label>
                        <textarea name="concern[chief_complaint]" rows="4" @readonly(!$canEdit)>{{ $oldOrPayload('concern.chief_complaint') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Onset</label>
                            <div class="flex gap-4">
                                @foreach(['Sudden', 'Gradual', 'Cannot recall'] as $opt)
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="concern[onset]" value="{{ $opt }}" {{ $oldOrPayload('concern.onset') === $opt ? 'checked' : '' }} @disabled(!$canEdit)>
                                    <span class="text-sm">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Duration</label>
                            <div class="flex gap-4">
                                @foreach(['<3 months', '3–12 months', '>1 year'] as $opt)
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="concern[duration]" value="{{ $opt }}" {{ $oldOrPayload('concern.duration') === $opt ? 'checked' : '' }} @disabled(!$canEdit)>
                                    <span class="text-sm">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Stress Level (0–10 | 0: Calm, 10: Overwhelmed)</label>
                        <input type="number" name="concern[stress_level]" min="0" max="10" value="{{ $oldOrPayload('concern.stress_level') }}" @readonly(!$canEdit)>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Coping Mechanisms</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach(['Avoidance', 'Overworking', 'Emotional eating', 'Withdrawal', 'Spiritual practice', 'Talking to someone', 'Substance use', 'Other'] as $opt)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="concern[coping][]" value="{{ $opt }}" {{ $isChecked($oldOrPayload('concern.coping', []), $opt) ? 'checked' : '' }} @disabled(!$canEdit)>
                                <span class="text-sm">{{ $opt }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-red-600 border-b pb-2">SECTION 4.2: RISK SCREENING (Mandatory)</h2>
                    <p class="text-xs text-gray-500">ASQ-style Suicide Risk Screening</p>
                    <div class="space-y-4">
                        @php
                            $riskQuestions = [
                                'q1' => 'In the past few weeks, have you wished you were dead?',
                                'q2' => 'In the past few weeks, have you felt that you or your family would be better off if you were dead?',
                                'q3' => 'In the past week, have you been having thoughts about killing yourself?',
                                'q4' => 'Have you ever tried to kill yourself?',
                                'q5' => 'Are you having thoughts of killing yourself right now?'
                            ];
                        @endphp
                        @foreach($riskQuestions as $key => $q)
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium">{{ $q }}</span>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="risk[{{ $key }}]" value="Yes" {{ $oldOrPayload("risk.$key") === 'Yes' ? 'checked' : '' }} @disabled(!$canEdit)>
                                    <span class="text-sm">Yes</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="risk[{{ $key }}]" value="No" {{ $oldOrPayload("risk.$key") === 'No' ? 'checked' : '' }} @disabled(!$canEdit)>
                                    <span class="text-sm">No</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Risk Notes / Clinical Impression</label>
                            <textarea name="risk[notes]" rows="3" @readonly(!$canEdit)>{{ $oldOrPayload('risk.notes') }}</textarea>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Part 3: Antarayas -->
            <div data-tab-panel="antarayas" class="consultation-tab-panel space-y-6" id="tab-antarayas">
                <section class="space-y-4 overflow-x-auto">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 5: ANTARAYAS ASSESSMENT</h2>
                    <p class="text-xs text-gray-500">Score 0–10 | 0 = Absent | 10 = Severe Interference</p>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left p-2">Obstacle (Antaraya)</th>
                                <th class="text-left p-2 w-24">Score</th>
                                <th class="text-left p-2">Clinical Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $obstacles = [
                                    'vyadhi' => ['label' => 'Vyadhi', 'meaning' => 'Disease / health issues'],
                                    'styana' => ['label' => 'Styana', 'meaning' => 'Mental lethargy / stagnation'],
                                    'samsaya' => ['label' => 'Samsaya', 'meaning' => 'Doubt / mistrust'],
                                    'pramada' => ['label' => 'Pramada', 'meaning' => 'Lack of enthusiasm'],
                                    'alasya' => ['label' => 'Alasya', 'meaning' => 'Lethargy / Laziness'],
                                    'avirati' => ['label' => 'Avirati', 'meaning' => 'Clinging to sensory pleasures'],
                                    'bhranti_darshana' => ['label' => 'Bhranti Darshana', 'meaning' => 'False perception / over-thinking'],
                                    'alabdha_bhumikatva' => ['label' => 'Alabdha Bhumikatva', 'meaning' => 'Inability to focus'],
                                    'anavasthitatva' => ['label' => 'Anavasthitatva', 'meaning' => 'Falling from attained state']
                                ];
                            @endphp
                            @foreach($obstacles as $key => $info)
                            <tr class="border-b">
                                <td class="p-2">
                                    <div class="font-bold">{{ $info['label'] }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $info['meaning'] }}</div>
                                </td>
                                <td class="p-2">
                                    <input type="number" name="antarayas[{{ $key }}][score]" min="0" max="10" value="{{ $oldOrPayload("antarayas.$key.score") }}" class="w-20" @readonly(!$canEdit)>
                                </td>
                                <td class="p-2">
                                    <input type="text" name="antarayas[{{ $key }}][remarks]" value="{{ $oldOrPayload("antarayas.$key.remarks") }}" class="w-full" @readonly(!$canEdit)>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>

            <!-- Part 4: Panchakosha -->
            <div data-tab-panel="panchakosha" class="consultation-tab-panel space-y-6" id="tab-panchakosha">
                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 6: PANCHAKOSHA ASSESSMENT</h2>
                    <p class="text-xs text-gray-500">Score 0–10 | 0 = No relation | 10 = Absolutely agree</p>
                    
                    @php
                        $koshas = [
                            'Annamaya' => ['Physical', [
                                'Daily hassles or struggles giving fatigue / strain',
                                'Any significant illness or an accident related stress',
                                'A quarrel/ difference of opinion is a source of stress',
                                'Pressure / competition',
                                'More reliance on look and appearance'
                            ]],
                            'Pranamaya' => ['Energy', [
                                'Difficulty in calming oneself down or relaxing',
                                'Breathing related problems or irregular breathing',
                                'Digestive system disturbances',
                                'Issues of pain / inflammation in the body',
                                'Disturbed sleep – insomnia, broken patterns'
                            ]],
                            'Manomaya' => ['Emotional', [
                                'Non-creative aptitude / sudden loss of creativity',
                                'Easily perturbed emotionally',
                                'Difficulty focusing/ concentrating',
                                'Tend to be too ambitious, competitive, jealous',
                                'Difficulty in friendships / relationships'
                            ]],
                            'Vijnanamaya' => ['Wisdom', [
                                'Lack of purpose or meaning in life',
                                'Low self-worth, low self esteem',
                                'Dealing with trauma / grief',
                                'Going through Moral dilemma',
                                'Loss of enthusiasm'
                            ]],
                            'Anandamaya' => ['Bliss', [
                                'Inability to feel joy or meaning in life',
                                'Sense of void / sudden gust of sorrow',
                                'Emotional numbness',
                                'Feeling disconnected within',
                                'Feeling unloved despite close connections'
                            ]]
                        ];
                    @endphp

                    @foreach($koshas as $name => $data)
                    <div class="p-4 bg-gray-50 rounded-xl space-y-3">
                        <h3 class="font-bold text-secondary">{{ $name }} Kosha <span class="text-xs font-normal text-gray-400">({{ $data[0] }} Layer)</span></h3>
                        <div class="space-y-2">
                            @foreach($data[1] as $idx => $item)
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-xs flex-1">{{ $item }}</span>
                                <input type="number" name="kosha[{{ $name }}][{{ $idx }}]" min="0" max="10" value="{{ $oldOrPayload("kosha.$name.$idx") }}" class="w-16" @readonly(!$canEdit)>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </section>
            </div>

            <!-- Part 5: Vritti -->
            <div data-tab-panel="vritti" class="consultation-tab-panel space-y-6" id="tab-vritti">
                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 9: VRITTI ASSESSMENT</h2>
                    <p class="text-xs text-gray-500">Rate 1–5 | 1: Never, 5: Very Often</p>

                    @php
                        $vrittis = [
                            'Pramana' => 'Valid Cognition (Logic, Evidence, Tradition)',
                            'Viparyaya' => 'Misperception (Misunderstanding, Distorted fears)',
                            'Vikalpa' => 'Imagination (Conceptual construction, Stories)',
                            'Nidra' => 'Sleep State (Dullness, Heaviness, Dreams)',
                            'Smriti' => 'Memory (Past influences, Reminiscing, Trauma)'
                        ];
                    @endphp

                    @foreach($vrittis as $key => $label)
                    <div class="p-4 border border-gray-100 rounded-xl space-y-3">
                        <h3 class="font-bold text-secondary">{{ $key }} <span class="text-xs font-normal text-gray-400">({{ $label }})</span></h3>
                        <div>
                            <label class="text-[10px] text-gray-400 uppercase font-black">Clinical Summary / Observed Patterns</label>
                            <textarea name="vritti[{{ $key }}][notes]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload("vritti.$key.notes") }}</textarea>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs font-bold">Aggregate Intensity (1-25)</label>
                            <input type="number" name="vritti[{{ $key }}][score]" min="5" max="25" value="{{ $oldOrPayload("vritti.$key.score") }}" class="w-20" @readonly(!$canEdit)>
                        </div>
                    </div>
                    @endforeach
                </section>
            </div>

            <!-- Part 6: Treatment Plan -->
            <div data-tab-panel="treatment" class="consultation-tab-panel space-y-6" id="tab-treatment">
                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 10: INTEGRATED TREATMENT PLAN</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Short-Term Goals (2–4 weeks)</label>
                            <textarea name="plan[goals_short]" rows="3" @readonly(!$canEdit)>{{ $oldOrPayload('plan.goals_short') }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Long-Term Goals (8–12 weeks)</label>
                            <textarea name="plan[goals_long]" rows="3" @readonly(!$canEdit)>{{ $oldOrPayload('plan.goals_long') }}</textarea>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-bold text-secondary mb-2">Mental Discipline (Abhyasa)</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-400">Ek Tattva Abhyasa (Single consistent practice)</label>
                                <textarea name="plan[ek_tattva]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('plan.ek_tattva') }}</textarea>
                            </div>
                            <div>
                                <label class="text-xs text-gray-400">Citta vikshepa (Counter-thoughts cultivation)</label>
                                <textarea name="plan[citta_vikshepa]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('plan.citta_vikshepa') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Session Frequency</label>
                            <select name="plan[frequency]" @disabled(!$canEdit)>
                                <option value="">Select...</option>
                                @foreach(['Weekly', 'Biweekly', 'Monthly'] as $opt)
                                <option value="{{ $opt }}" {{ $oldOrPayload('plan.frequency') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Next Review Date</label>
                            <input type="date" name="plan[review_date]" value="{{ $oldOrPayload('plan.review_date') }}" @readonly(!$canEdit)>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Part 7: Progress -->
            <div data-tab-panel="progress" class="consultation-tab-panel space-y-6" id="tab-progress">
                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 11: PROGRESS NOTES (SOAP)</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">S – Subjective (Patient reports)</label>
                            <textarea name="soap[subjective]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('soap.subjective') }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">O – Objective (Observed posture, breath, affect)</label>
                            <textarea name="soap[objective]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('soap.objective') }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">A – Assessment (Changes in stress/kosha/clarity)</label>
                            <textarea name="soap[assessment]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('soap.assessment') }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">P – Plan (Modifications for next session)</label>
                            <textarea name="soap[plan]" rows="2" @readonly(!$canEdit)>{{ $oldOrPayload('soap.plan') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-secondary border-b pb-2">SECTION 13: DISCHARGE / CONTINUATION</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Status</label>
                            <div class="space-y-1">
                                @foreach(['Goals achieved', 'Referred', 'Discontinued', 'Non-compliance'] as $opt)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="discharge[status][]" value="{{ $opt }}" {{ $isChecked($oldOrPayload('discharge.status', []), $opt) ? 'checked' : '' }} @disabled(!$canEdit)>
                                    <span class="text-sm">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Final Clinical Summary</label>
                            <textarea name="discharge[summary]" rows="4" @readonly(!$canEdit)>{{ $oldOrPayload('discharge.summary') }}</textarea>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Sticky Bottom Actions -->
        <div class="flex flex-wrap items-center justify-between gap-4 pt-8 mt-4 border-t border-gray-100">
            <div class="flex gap-3">
                <button type="button" id="consultation-prev-tab" class="hidden px-6 py-3 rounded-full border border-gray-200 text-sm font-bold text-secondary hover:bg-gray-50 transition-all">
                    <i class="ri-arrow-left-line mr-2"></i> Previous Section
                </button>
                <button type="button" id="consultation-next-tab" class="px-6 py-3 rounded-full bg-secondary text-white text-sm font-bold hover:bg-primary transition-all">
                    Next Section <i class="ri-arrow-right-line ml-2"></i>
                </button>
            </div>

            <div class="flex gap-3">
                @if($canEdit)
                <button type="submit" class="px-8 py-3 rounded-full bg-secondary text-white text-sm font-bold hover:shadow-lg transition-all">
                    <i class="ri-save-line mr-2"></i> Save Consultation
                </button>
                @endif
                <a href="{{ route('bookings.index') }}" class="px-6 py-3 rounded-full border border-gray-200 text-sm font-bold text-gray-400 hover:text-gray-600 transition-all">
                    Exit
                </a>
            </div>
        </div>
    </div>
</form>