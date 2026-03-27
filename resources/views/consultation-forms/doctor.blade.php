@php
    $oldOrPayload = fn (string $key, $default = null) => old($key, data_get($payload, $key, $default));
    $isChecked = fn ($values, string $option) => in_array($option, is_array($values) ? $values : (blank($values) ? [] : [$values]), true);

    $presentingComplaints = old('presenting_complaints', data_get($payload, 'presenting_complaints', []));
    $presentingComplaints = is_array($presentingComplaints) ? array_values($presentingComplaints) : [];
    while (count($presentingComplaints) < 3) {
        $presentingComplaints[] = ['complaint' => '', 'duration' => '', 'detailed_history' => ''];
    }

    $pastMedicalLabels = [
        'High Blood Pressure',
        'Cardiac Arrest / Heart Failure',
        'High Cholesterol',
        'Stroke / TIA',
        'Diabetes',
        'Thyroid Disease',
        'Cancer',
        'Kidney Disease',
    ];
    $pastMedicalHistory = old('past_medical_history', data_get($payload, 'past_medical_history', []));
    $pastMedicalHistory = is_array($pastMedicalHistory) ? array_values($pastMedicalHistory) : [];
    $pastMedicalRows = [];
    foreach ($pastMedicalLabels as $index => $label) {
        $pastMedicalRows[] = array_merge([
            'condition' => $label,
            'duration_or_date_of_diagnosis' => '',
            'notes' => '',
        ], is_array($pastMedicalHistory[$index] ?? null) ? $pastMedicalHistory[$index] : []);
    }
    if (count($pastMedicalHistory) > count($pastMedicalLabels)) {
        foreach (array_slice($pastMedicalHistory, count($pastMedicalLabels)) as $row) {
            $pastMedicalRows[] = array_merge([
                'condition' => '',
                'duration_or_date_of_diagnosis' => '',
                'notes' => '',
            ], is_array($row) ? $row : []);
        }
    }

    $familyHistory = old('family_history', data_get($payload, 'family_history', []));
    $familyHistory = is_array($familyHistory) ? array_values($familyHistory) : [];
    if (!count($familyHistory)) {
        $familyHistory[] = ['disorder' => '', 'affected_family_member' => '', 'notes' => ''];
    }
@endphp

<form id="doctor-consultation-form" method="POST" action="{{ route('bookings.consultation-form.store', $booking->id) }}" class="consultation-form-root space-y-6">
    @csrf

    <div class="space-y-6">
        <div class="consultation-tab-controls">
            <div class="consultation-tabs" role="tablist">
                <button type="button" class="consultation-tab-button is-active" data-tab="complaints" aria-controls="tab-complaints" aria-selected="true">
                    <span class="consultation-tab-title">Complaints & History</span>
                    <span class="consultation-tab-subtitle">Presenting, medical, family</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="systems" aria-controls="tab-systems" aria-selected="false">
                    <span class="consultation-tab-title">Personal & Systems</span>
                    <span class="consultation-tab-subtitle">Habits, observations, exams</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="clinical" aria-controls="tab-clinical" aria-selected="false">
                    <span class="consultation-tab-title">Clinical Data</span>
                    <span class="consultation-tab-subtitle">Investigations, meds, summary</span>
                </button>
                <button type="button" class="consultation-tab-button" data-tab="plan" aria-controls="tab-plan" aria-selected="false">
                    <span class="consultation-tab-title">Plan & Advice</span>
                    <span class="consultation-tab-subtitle">Treatment, lifestyle, follow-up</span>
                </button>
            </div>
        </div>
            <div data-tab-panel="complaints" class="consultation-tab-panel space-y-6 is-active" id="tab-complaints">
                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
                    <h2 class="text-lg font-semibold text-secondary mb-3">Presenting Complaints</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-2">Complaint</th>
                                <th class="text-left p-2">Duration</th>
                                <th class="text-left p-2">Detailed History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presentingComplaints as $index => $row)
                            <tr>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="presenting_complaints[{{ $index }}][complaint]" value="{{ $row['complaint'] }}" placeholder="Complaint"></td>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="presenting_complaints[{{ $index }}][duration]" value="{{ $row['duration'] }}" placeholder="Duration"></td>
                                <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="presenting_complaints[{{ $index }}][detailed_history]" placeholder="Onset, progression, previous treatments">{{ $row['detailed_history'] }}</textarea></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>

                <section data-repeat-section="past_medical_history" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
                    <h2 class="text-lg font-semibold text-secondary mb-3">Past Medical History</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-2">Condition</th>
                                <th class="text-left p-2">Duration / Date of Diagnosis</th>
                                <th class="text-left p-2">Notes</th>
                                <th class="text-left p-2"></th>
                            </tr>
                        </thead>
                        <tbody data-repeat-body="past_medical_history">
                            @foreach($pastMedicalRows as $index => $row)
                            <tr data-repeat-row data-row-index="{{ $index }}">
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="past_medical_history[{{ $index }}][condition]" value="{{ $row['condition'] }}" placeholder="Condition"></td>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="past_medical_history[{{ $index }}][duration_or_date_of_diagnosis]" value="{{ $row['duration_or_date_of_diagnosis'] }}" placeholder="Duration or date"></td>
                                <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="past_medical_history[{{ $index }}][notes]" placeholder="Notes">{{ $row['notes'] }}</textarea></td>
                                <td class="p-2">
                                    <button type="button" class="text-xs font-semibold text-red-600" data-repeat-remove="past_medical_history">Remove</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" data-repeat-add="past_medical_history" class="text-sm font-semibold text-secondary hover:text-primary">
                        <i class="ri-add-line"></i> Add other condition
                    </button>
                    <template data-repeat-template="past_medical_history">
                        <tr data-repeat-row data-row-index="__INDEX__">
                            <td class="p-2"><input class="w-full border rounded px-3 py-2" name="past_medical_history[__INDEX__][condition]" placeholder="Condition"></td>
                            <td class="p-2"><input class="w-full border rounded px-3 py-2" name="past_medical_history[__INDEX__][duration_or_date_of_diagnosis]" placeholder="Duration or date"></td>
                            <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="past_medical_history[__INDEX__][notes]" placeholder="Notes"></textarea></td>
                            <td class="p-2">
                                <button type="button" class="text-xs font-semibold text-red-600" data-repeat-remove="past_medical_history">Remove</button>
                            </td>
                        </tr>
                    </template>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
                    <h2 class="text-lg font-semibold text-secondary mb-3">Family History</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-2">Disorder</th>
                                <th class="text-left p-2">Affected Family Member</th>
                                <th class="text-left p-2">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($familyHistory as $index => $row)
                            <tr>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="family_history[{{ $index }}][disorder]" value="{{ $row['disorder'] }}" placeholder="Disorder"></td>
                                <td class="p-2">
                                    <select class="w-full border rounded px-3 py-2" name="family_history[{{ $index }}][affected_family_member]">
                                        <option value="">Select</option>
                                        @foreach(['Mother','Father','Brother','Sister','Maternal Grandparents','Paternal Grandparents'] as $member)
                                            <option value="{{ $member }}" @selected($row['affected_family_member'] === $member)>{{ $member }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="family_history[{{ $index }}][notes]" placeholder="Notes">{{ $row['notes'] }}</textarea></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>
                <div data-tab-panel="systems" class="consultation-tab-panel space-y-6" id="tab-systems">
            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
            <h2 class="text-lg font-semibold text-secondary mb-3">Personal History</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="font-medium mb-2">Bowel</h3>
                    <div class="grid md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <label>Regularity</label>
                            <div class="flex gap-4">
                                @foreach(['Regular', 'Irregular'] as $option)
                                    <label><input type="radio" name="personal_history[bowel][regularity]" value="{{ $option }}" @checked($oldOrPayload('personal_history.bowel.regularity') === $option)> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label>Consistency</label>
                            <div class="flex flex-wrap gap-4">
                                @foreach(['Constipated', 'Loose', 'Paste-like'] as $option)
                                    <label><input type="checkbox" name="personal_history[bowel][consistency][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.bowel.consistency'), $option))> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <label><input type="checkbox" name="personal_history[bowel][pain_or_burning]" value="1" @checked((bool) $oldOrPayload('personal_history.bowel.pain_or_burning'))> Pain or burning during or after evacuation</label>
                        <input class="border rounded px-3 py-2" type="number" name="personal_history[bowel][frequency_per_day]" value="{{ $oldOrPayload('personal_history.bowel.frequency_per_day') }}" placeholder="Frequency per day">
                        <div>
                            <label>Timing</label>
                            <div class="flex gap-4">
                                @foreach(['Before food', 'After food'] as $option)
                                    <label><input type="checkbox" name="personal_history[bowel][timing][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.bowel.timing'), $option))> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <input class="border rounded px-3 py-2" type="text" name="personal_history[bowel][other]" value="{{ $oldOrPayload('personal_history.bowel.other') }}" placeholder="Other">
                    </div>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Micturition</h3>
                    <div class="grid md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <label>Colour</label>
                            <div class="flex gap-4">
                                @foreach(['Clear', 'Yellowish'] as $option)
                                    <label><input type="radio" name="personal_history[micturition][colour]" value="{{ $option }}" @checked($oldOrPayload('personal_history.micturition.colour') === $option)> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label>Conditions</label>
                            <div class="flex flex-wrap gap-4">
                                @foreach(['Incontinence', 'Difficulty Voiding', 'Burning', 'Blood in Urine'] as $option)
                                    <label><input type="checkbox" name="personal_history[micturition][conditions][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.micturition.conditions'), $option))> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <input class="border rounded px-3 py-2" type="number" name="personal_history[micturition][frequency_per_day]" value="{{ $oldOrPayload('personal_history.micturition.frequency_per_day') }}" placeholder="Frequency per day">
                        <input class="border rounded px-3 py-2" type="text" name="personal_history[micturition][other]" value="{{ $oldOrPayload('personal_history.micturition.other') }}" placeholder="Other">
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-2">Water Intake</label>
                    <input class="border rounded px-3 py-2 w-full" type="text" name="personal_history[water_intake][average_quantity_per_day]" value="{{ $oldOrPayload('personal_history.water_intake.average_quantity_per_day') }}" placeholder="Average quantity per day">
                </div>

                <div>
                    <h3 class="font-medium mb-2">Sleep</h3>
                    <div class="grid md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <label>Quality</label>
                            <div class="flex gap-4">
                                @foreach(['Sound', 'Disturbed'] as $option)
                                    <label><input type="radio" name="personal_history[sleep][quality]" value="{{ $option }}" @checked($oldOrPayload('personal_history.sleep.quality') === $option)> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label>Naps</label>
                            <div class="flex gap-4">
                                @foreach(['Yes', 'No'] as $option)
                                    <label><input type="radio" name="personal_history[sleep][naps]" value="{{ $option }}" @checked($oldOrPayload('personal_history.sleep.naps') === $option)> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <input class="border rounded px-3 py-2" type="time" name="personal_history[sleep][bedtime]" value="{{ $oldOrPayload('personal_history.sleep.bedtime') }}" placeholder="Bedtime">
                        <input class="border rounded px-3 py-2" type="text" name="personal_history[sleep][time_to_fall_asleep]" value="{{ $oldOrPayload('personal_history.sleep.time_to_fall_asleep') }}" placeholder="Time to fall asleep">
                        <input class="border rounded px-3 py-2" type="text" name="personal_history[sleep][night_awakenings]" value="{{ $oldOrPayload('personal_history.sleep.night_awakenings') }}" placeholder="Night awakenings">
                        <input class="border rounded px-3 py-2" type="text" name="personal_history[sleep][time_to_get_back_to_sleep]" value="{{ $oldOrPayload('personal_history.sleep.time_to_get_back_to_sleep') }}" placeholder="Time to get back to sleep">
                        <input class="border rounded px-3 py-2" type="time" name="personal_history[sleep][wake_up_time]" value="{{ $oldOrPayload('personal_history.sleep.wake_up_time') }}" placeholder="Wake up time">
                        <input class="border rounded px-3 py-2" type="time" name="personal_history[sleep][getting_out_of_bed]" value="{{ $oldOrPayload('personal_history.sleep.getting_out_of_bed') }}" placeholder="Getting out of bed">
                        <label><input type="checkbox" name="personal_history[sleep][dreams]" value="1" @checked((bool) $oldOrPayload('personal_history.sleep.dreams'))> Dreams</label>
                        <label><input type="checkbox" name="personal_history[sleep][nightmares]" value="1" @checked((bool) $oldOrPayload('personal_history.sleep.nightmares'))> Nightmares</label>
                        <textarea class="border rounded px-3 py-2 md:col-span-2" rows="2" name="personal_history[sleep][comments]" placeholder="Comments">{{ $oldOrPayload('personal_history.sleep.comments') }}</textarea>
                    </div>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Appetite</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-3">
                        @foreach(['Good', 'Moderate', 'Poor'] as $option)
                            <label><input type="radio" name="personal_history[appetite][level]" value="{{ $option }}" @checked($oldOrPayload('personal_history.appetite.level') === $option)> {{ $option }}</label>
                        @endforeach
                    </div>
                    <label><input type="checkbox" name="personal_history[appetite][strong_digestive_fire]" value="1" @checked((bool) $oldOrPayload('personal_history.appetite.strong_digestive_fire'))> Feels digestive fire is strong</label>
                    <textarea class="border rounded px-3 py-2 w-full mt-3" rows="2" name="personal_history[appetite][comments]" placeholder="Comments">{{ $oldOrPayload('personal_history.appetite.comments') }}</textarea>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Gastric Distress</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-3">
                        @foreach(['Abdominal Pain', 'Bloating', 'Nausea', 'Acid Reflux', 'Burning Sensation'] as $option)
                            <label><input type="checkbox" name="personal_history[gastric_distress][symptoms][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.gastric_distress.symptoms'), $option))> {{ $option }}</label>
                        @endforeach
                    </div>
                    <textarea class="border rounded px-3 py-2 w-full" rows="2" name="personal_history[gastric_distress][comments]" placeholder="Comments">{{ $oldOrPayload('personal_history.gastric_distress.comments') }}</textarea>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Diet</h3>
                    <div class="grid md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <label>Type</label>
                            <div class="flex gap-4">
                                @foreach(['Vegetarian', 'Non-Vegetarian', 'Mixed'] as $option)
                                    <label><input type="radio" name="personal_history[diet][type]" value="{{ $option }}" @checked($oldOrPayload('personal_history.diet.type') === $option)> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label>Quantity per meal</label>
                            <div class="flex gap-4">
                                @foreach(['Small', 'Moderate', 'Large'] as $option)
                                    <label><input type="radio" name="personal_history[diet][quantity_per_meal]" value="{{ $option }}" @checked($oldOrPayload('personal_history.diet.quantity_per_meal') === $option)> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label>Timing</label>
                            <div class="flex gap-4">
                                @foreach(['Timely intake', 'Irregular'] as $option)
                                    <label><input type="checkbox" name="personal_history[diet][timing][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.diet.timing'), $option))> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label>Food type</label>
                            <div class="flex gap-4">
                                @foreach(['Junk food', 'Homely food'] as $option)
                                    <label><input type="checkbox" name="personal_history[diet][food_type][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.diet.food_type'), $option))> {{ $option }}</label>
                                @endforeach
                            </div>
                        </div>
                        <input class="border rounded px-3 py-2" type="text" name="personal_history[diet][breakfast]" value="{{ $oldOrPayload('personal_history.diet.breakfast') }}" placeholder="Breakfast">
                        <input class="border rounded px-3 py-2" type="text" name="personal_history[diet][lunch]" value="{{ $oldOrPayload('personal_history.diet.lunch') }}" placeholder="Lunch">
                        <input class="border rounded px-3 py-2" type="text" name="personal_history[diet][dinner]" value="{{ $oldOrPayload('personal_history.diet.dinner') }}" placeholder="Dinner">
                        <textarea class="border rounded px-3 py-2 md:col-span-2" rows="2" name="personal_history[diet][comments]" placeholder="Comments">{{ $oldOrPayload('personal_history.diet.comments') }}</textarea>
                    </div>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Allergies</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-3">
                        @foreach(['Food', 'Environmental'] as $option)
                            <label><input type="checkbox" name="personal_history[allergies][types][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.allergies.types'), $option))> {{ $option }}</label>
                        @endforeach
                    </div>
                    <input class="border rounded px-3 py-2 w-full" type="text" name="personal_history[allergies][other]" value="{{ $oldOrPayload('personal_history.allergies.other') }}" placeholder="Other">
                </div>

                <div>
                    <h3 class="font-medium mb-2">Addictions / Habits</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-3">
                        @foreach(['Alcohol', 'Smoking', 'Coffee', 'Sugar'] as $option)
                            <label><input type="checkbox" name="personal_history[addictions_habits][types][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.addictions_habits.types'), $option))> {{ $option }}</label>
                        @endforeach
                    </div>
                    <input class="border rounded px-3 py-2 w-full" type="text" name="personal_history[addictions_habits][other]" value="{{ $oldOrPayload('personal_history.addictions_habits.other') }}" placeholder="Other">
                </div>

                <div>
                    <h3 class="font-medium mb-2">Nature of Work</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-3">
                        @foreach(['Sedentary', 'Sitting', 'Standing', 'Travelling'] as $option)
                            <label><input type="checkbox" name="personal_history[nature_of_work][types][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.nature_of_work.types'), $option))> {{ $option }}</label>
                        @endforeach
                    </div>
                    <textarea class="border rounded px-3 py-2 w-full" rows="2" name="personal_history[nature_of_work][comments]" placeholder="Comments">{{ $oldOrPayload('personal_history.nature_of_work.comments') }}</textarea>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Exercise / Sports</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-3">
                        @foreach(['No Exercise', 'Mild', 'Occasional Vigorous', 'Regular Vigorous'] as $option)
                            <label><input type="radio" name="personal_history[exercise_sports][level]" value="{{ $option }}" @checked($oldOrPayload('personal_history.exercise_sports.level') === $option)> {{ $option }}</label>
                        @endforeach
                    </div>
                    <textarea class="border rounded px-3 py-2 w-full" rows="2" name="personal_history[exercise_sports][comments]" placeholder="Comments">{{ $oldOrPayload('personal_history.exercise_sports.comments') }}</textarea>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Psychological / Emotional Status</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-3">
                        @foreach(['Anxious', 'Fearful', 'Depressed', 'Stressed', 'Arrogant', 'Confident in decisions', 'Confused'] as $option)
                            <label><input type="checkbox" name="personal_history[psychological_status][states][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('personal_history.psychological_status.states'), $option))> {{ $option }}</label>
                        @endforeach
                    </div>
                    <label><input type="checkbox" name="personal_history[psychological_status][history_of_trauma]" value="1" @checked((bool) $oldOrPayload('personal_history.psychological_status.history_of_trauma'))> History of trauma</label>
                    <textarea class="border rounded px-3 py-2 w-full mt-3" rows="2" name="personal_history[psychological_status][comments]" placeholder="Comments">{{ $oldOrPayload('personal_history.psychological_status.comments') }}</textarea>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Physical Strength</h3>
                    <div class="flex gap-4 text-sm mb-3">
                        @foreach(['Good', 'Fatigue'] as $option)
                            <label><input type="radio" name="personal_history[physical_strength][status]" value="{{ $option }}" @checked($oldOrPayload('personal_history.physical_strength.status') === $option)> {{ $option }}</label>
                        @endforeach
                    </div>
                    <textarea class="border rounded px-3 py-2 w-full" rows="2" name="personal_history[physical_strength][comments]" placeholder="Comments">{{ $oldOrPayload('personal_history.physical_strength.comments') }}</textarea>
                </div>
            </div>
        </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
            <h2 class="text-lg font-semibold text-secondary mb-3">Sensory and Systemic Observations</h2>
            <div class="grid md:grid-cols-2 gap-3">
                @foreach([
                    'respiration_breathing' => 'Respiration / Breathing',
                    'sensitivity_to_light' => 'Sensitivity to Light',
                    'sensitivity_to_noise' => 'Sensitivity to Noise',
                    'heat_cold_sensation' => 'Heat / Cold Sensation',
                    'sweat' => 'Sweat',
                    'blood_pressure' => 'Blood Pressure',
                    'palpitation' => 'Palpitation',
                    'headache_migraine_vertigo' => 'Headache / Migraine / Vertigo',
                    'speech_voice' => 'Speech / Voice',
                    'skin' => 'Skin',
                    'eyes_vision' => 'Eyes / Vision',
                    'tinnitus' => 'Tinnitus',
                    'ears_hearing' => 'Ears / Hearing',
                    'tongue' => 'Tongue',
                    'nails' => 'Nails',
                    'hair' => 'Hair',
                ] as $key => $label)
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ $label }}</label>
                        <input class="border rounded px-3 py-2 w-full" type="text" name="sensory_systemic_observations[{{ $key }}]" value="{{ $oldOrPayload('sensory_systemic_observations.' . $key) }}">
                    </div>
                @endforeach
            </div>
        </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
            <h2 class="text-lg font-semibold text-secondary mb-3">Anthropometrics</h2>
            <div class="grid md:grid-cols-3 gap-3">
                <input class="border rounded px-3 py-2" type="number" step="0.1" name="anthropometrics[height_cm]" value="{{ $oldOrPayload('anthropometrics.height_cm') }}" placeholder="Height (cm)">
                <input class="border rounded px-3 py-2" type="number" step="0.1" name="anthropometrics[weight_kg]" value="{{ $oldOrPayload('anthropometrics.weight_kg') }}" placeholder="Weight (kg)">
                <input class="border rounded px-3 py-2" type="number" step="0.1" name="anthropometrics[bmi]" value="{{ $oldOrPayload('anthropometrics.bmi') }}" placeholder="BMI" readonly>
            </div>
            <textarea class="border rounded px-3 py-2 w-full mt-3" rows="2" name="anthropometrics[comments]" placeholder="Comments">{{ $oldOrPayload('anthropometrics.comments') }}</textarea>
        </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
            <h2 class="text-lg font-semibold text-secondary mb-3">Menstrual / Reproductive History</h2>
            <div class="grid md:grid-cols-2 gap-3">
                <input class="border rounded px-3 py-2" type="number" name="menstrual_reproductive_history[age_of_menarche]" value="{{ $oldOrPayload('menstrual_reproductive_history.age_of_menarche') }}" placeholder="Age of menarche">
                <input class="border rounded px-3 py-2" type="date" name="menstrual_reproductive_history[lmp]" value="{{ $oldOrPayload('menstrual_reproductive_history.lmp') }}" placeholder="LMP">
                <input class="border rounded px-3 py-2" type="text" name="menstrual_reproductive_history[interval]" value="{{ $oldOrPayload('menstrual_reproductive_history.interval') }}" placeholder="Interval">
                <input class="border rounded px-3 py-2" type="text" name="menstrual_reproductive_history[duration]" value="{{ $oldOrPayload('menstrual_reproductive_history.duration') }}" placeholder="Duration">
                <div>
                    <label class="block text-sm font-medium mb-1">Cycles</label>
                    <div class="flex gap-4">
                        @foreach(['Regular', 'Irregular'] as $option)
                            <label><input type="radio" name="menstrual_reproductive_history[cycles]" value="{{ $option }}" @checked($oldOrPayload('menstrual_reproductive_history.cycles') === $option)> {{ $option }}</label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Bleeding</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['Spotting', 'Scanty', 'Moderate', 'Excessive'] as $option)
                            <label><input type="radio" name="menstrual_reproductive_history[bleeding]" value="{{ $option }}" @checked($oldOrPayload('menstrual_reproductive_history.bleeding') === $option)> {{ $option }}</label>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Associated Complaints</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['Pain', 'Nausea', 'Vomiting', 'Constipation', 'Diarrhoea'] as $option)
                            <label><input type="checkbox" name="menstrual_reproductive_history[associated_complaints][]" value="{{ $option }}" @checked($isChecked($oldOrPayload('menstrual_reproductive_history.associated_complaints'), $option))> {{ $option }}</label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Menopause</label>
                    <div class="flex gap-4">
                        @foreach(['Attained', 'Not Attained'] as $option)
                            <label><input type="radio" name="menstrual_reproductive_history[menopause][attained]" value="{{ $option }}" @checked($oldOrPayload('menstrual_reproductive_history.menopause.attained') === $option)> {{ $option }}</label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <div class="flex gap-4">
                        @foreach(['Natural', 'Surgical'] as $option)
                            <label><input type="radio" name="menstrual_reproductive_history[menopause][type]" value="{{ $option }}" @checked($oldOrPayload('menstrual_reproductive_history.menopause.type') === $option)> {{ $option }}</label>
                        @endforeach
                    </div>
                </div>
                <input class="border rounded px-3 py-2" type="number" name="menstrual_reproductive_history[obstetric_history][number_of_pregnancies]" value="{{ $oldOrPayload('menstrual_reproductive_history.obstetric_history.number_of_pregnancies') }}" placeholder="Number of pregnancies">
                <input class="border rounded px-3 py-2" type="number" name="menstrual_reproductive_history[obstetric_history][number_of_labours]" value="{{ $oldOrPayload('menstrual_reproductive_history.obstetric_history.number_of_labours') }}" placeholder="Number of labours">
                <input class="border rounded px-3 py-2" type="number" name="menstrual_reproductive_history[obstetric_history][stillbirths]" value="{{ $oldOrPayload('menstrual_reproductive_history.obstetric_history.stillbirths') }}" placeholder="Stillbirths">
                <input class="border rounded px-3 py-2" type="number" name="menstrual_reproductive_history[obstetric_history][abortions]" value="{{ $oldOrPayload('menstrual_reproductive_history.obstetric_history.abortions') }}" placeholder="Abortions">
                <input class="border rounded px-3 py-2" type="number" name="menstrual_reproductive_history[obstetric_history][year_of_last_childbirth]" value="{{ $oldOrPayload('menstrual_reproductive_history.obstetric_history.year_of_last_childbirth') }}" placeholder="Year of last childbirth">
                <div>
                    <label class="block text-sm font-medium mb-1">Nature of labour</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['Normal', 'Caesarean', 'Forceps', 'Other'] as $option)
                            <label><input type="radio" name="menstrual_reproductive_history[obstetric_history][nature_of_labour]" value="{{ $option }}" @checked($oldOrPayload('menstrual_reproductive_history.obstetric_history.nature_of_labour') === $option)> {{ $option }}</label>
                        @endforeach
                    </div>
                </div>
                <textarea class="border rounded px-3 py-2 md:col-span-2" rows="2" name="menstrual_reproductive_history[history_of_infections]" placeholder="History of infections">{{ $oldOrPayload('menstrual_reproductive_history.history_of_infections') }}</textarea>
                <textarea class="border rounded px-3 py-2 md:col-span-2" rows="2" name="menstrual_reproductive_history[contraception_history]" placeholder="Contraception history">{{ $oldOrPayload('menstrual_reproductive_history.contraception_history') }}</textarea>
            </div>
        </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
            <h2 class="text-lg font-semibold text-secondary mb-3">Musculoskeletal and Edema</h2>
            <div class="grid md:grid-cols-2 gap-3">
                <textarea class="border rounded px-3 py-2" rows="3" name="musculoskeletal_edema[musculoskeletal_pain]" placeholder="Musculoskeletal pain">{{ $oldOrPayload('musculoskeletal_edema.musculoskeletal_pain') }}</textarea>
                <textarea class="border rounded px-3 py-2" rows="3" name="musculoskeletal_edema[edema]" placeholder="Edema">{{ $oldOrPayload('musculoskeletal_edema.edema') }}</textarea>
            </div>
        </section>
                </div>
                <div data-tab-panel="clinical" class="consultation-tab-panel space-y-6" id="tab-clinical">

        <section data-repeat-section="investigations" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
            <h2 class="text-lg font-semibold text-secondary mb-3">Investigations</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left p-2">Test</th>
                        <th class="text-left p-2">Result / Notes</th>
                        <th class="text-left p-2"></th>
                    </tr>
                </thead>
                <tbody data-repeat-body="investigations">
                    @php
                        $investigations = old('investigations', data_get($payload, 'investigations', []));
                        $investigations = is_array($investigations) ? array_values($investigations) : [];
                        while (count($investigations) < 2) {
                            $investigations[] = ['test' => '', 'result_notes' => ''];
                        }
                    @endphp
                    @foreach($investigations as $index => $row)
                    <tr data-repeat-row data-row-index="{{ $index }}">
                        <td class="p-2"><input class="w-full border rounded px-3 py-2" name="investigations[{{ $index }}][test]" value="{{ $row['test'] }}" placeholder="Test"></td>
                        <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="investigations[{{ $index }}][result_notes]" placeholder="Result / Notes">{{ $row['result_notes'] }}</textarea></td>
                        <td class="p-2">
                            <button type="button" class="text-xs font-semibold text-red-600" data-repeat-remove="investigations">Remove</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" data-repeat-add="investigations" class="text-sm font-semibold text-secondary hover:text-primary">
                <i class="ri-add-line"></i> Add investigation
            </button>
            <template data-repeat-template="investigations">
                <tr data-repeat-row data-row-index="__INDEX__">
                    <td class="p-2"><input class="w-full border rounded px-3 py-2" name="investigations[__INDEX__][test]" placeholder="Test"></td>
                    <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="investigations[__INDEX__][result_notes]" placeholder="Result / Notes"></textarea></td>
                    <td class="p-2">
                        <button type="button" class="text-xs font-semibold text-red-600" data-repeat-remove="investigations">Remove</button>
                    </td>
                </tr>
            </template>
        </section>

        <section data-repeat-section="current_medications_supplements" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
            <h2 class="text-lg font-semibold text-secondary mb-3">Current Medications / Supplements</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left p-2">Name</th>
                        <th class="text-left p-2">Dosage / Frequency</th>
                        <th class="text-left p-2"></th>
                    </tr>
                </thead>
                <tbody data-repeat-body="current_medications_supplements">
                    @php
                        $currentMeds = old('current_medications_supplements', data_get($payload, 'current_medications_supplements', []));
                        $currentMeds = is_array($currentMeds) ? array_values($currentMeds) : [];
                        while (count($currentMeds) < 2) {
                            $currentMeds[] = ['name' => '', 'dosage_frequency' => ''];
                        }
                    @endphp
                    @foreach($currentMeds as $index => $row)
                    <tr data-repeat-row data-row-index="{{ $index }}">
                        <td class="p-2"><input class="w-full border rounded px-3 py-2" name="current_medications_supplements[{{ $index }}][name]" value="{{ $row['name'] }}" placeholder="Name"></td>
                        <td class="p-2"><input class="w-full border rounded px-3 py-2" name="current_medications_supplements[{{ $index }}][dosage_frequency]" value="{{ $row['dosage_frequency'] }}" placeholder="Dosage / Frequency"></td>
                        <td class="p-2">
                            <button type="button" class="text-xs font-semibold text-red-600" data-repeat-remove="current_medications_supplements">Remove</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="text-sm font-semibold text-secondary hover:text-primary" data-repeat-add="current_medications_supplements">
                <i class="ri-add-line"></i> Add medication
            </button>
            <template data-repeat-template="current_medications_supplements">
                <tr data-repeat-row data-row-index="__INDEX__">
                    <td class="p-2"><input class="w-full border rounded px-3 py-2" name="current_medications_supplements[__INDEX__][name]" placeholder="Name"></td>
                    <td class="p-2"><input class="w-full border rounded px-3 py-2" name="current_medications_supplements[__INDEX__][dosage_frequency]" placeholder="Dosage / Frequency"></td>
                    <td class="p-2">
                        <button type="button" class="text-xs font-semibold text-red-600" data-repeat-remove="current_medications_supplements">Remove</button>
                    </td>
                </tr>
            </template>
        </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3 overflow-x-auto">
            <h2 class="text-lg font-semibold text-secondary mb-3">Ayurvedic Summary</h2>
            @php
                $constitutionLabels = [
                    'Prakriti',
                    'Vikriti',
                    'Manasika Prakriti',
                    'Physical Strength',
                    'Psychological Strength',
                    'Agni',
                    'Ama',
                    'Purisha',
                    'Koshta',
                    'Mutra',
                    'Dhatu',
                    'Ojas',
                    'Prana',
                    'Tejas (Metabolic Power)',
                    'Srotas',
                ];
                $constitutionRows = old('ayurvedic_summary.constitution_analysis', data_get($payload, 'ayurvedic_summary.constitution_analysis', []));
                $constitutionRows = is_array($constitutionRows) ? array_values($constitutionRows) : [];
                $constitutionList = [];
                foreach ($constitutionLabels as $index => $label) {
                    $constitutionList[] = array_merge([
                        'parameter' => $label,
                        'observation' => '',
                        'notes' => '',
                    ], is_array($constitutionRows[$index] ?? null) ? $constitutionRows[$index] : []);
                }

                $majorFindings = old('ayurvedic_summary.major_findings_from_investigation', data_get($payload, 'ayurvedic_summary.major_findings_from_investigation', []));
                $majorFindings = is_array($majorFindings) ? array_values($majorFindings) : [];
                if (!count($majorFindings)) {
                    $majorFindings[] = ['investigation' => '', 'range' => '', 'notes' => ''];
                }
            @endphp
            <div class="space-y-5">
                <div>
                    <h3 class="font-medium mb-2">Constitution Analysis</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-2">Parameter</th>
                                <th class="text-left p-2">Observation</th>
                                <th class="text-left p-2">Notes / Justification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($constitutionList as $index => $row)
                            <tr>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="ayurvedic_summary[constitution_analysis][{{ $index }}][parameter]" value="{{ $row['parameter'] }}" placeholder="Parameter"></td>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="ayurvedic_summary[constitution_analysis][{{ $index }}][observation]" value="{{ $row['observation'] }}" placeholder="Observation"></td>
                                <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="ayurvedic_summary[constitution_analysis][{{ $index }}][notes]" placeholder="Notes">{{ $row['notes'] }}</textarea></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Major Findings from Investigation</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-2">Investigation</th>
                                <th class="text-left p-2">Range</th>
                                <th class="text-left p-2">Notes / Conclusion / Justification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($majorFindings as $index => $row)
                            <tr>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="ayurvedic_summary[major_findings_from_investigation][{{ $index }}][investigation]" value="{{ $row['investigation'] }}" placeholder="Investigation"></td>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="ayurvedic_summary[major_findings_from_investigation][{{ $index }}][range]" value="{{ $row['range'] }}" placeholder="Range"></td>
                                <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="ayurvedic_summary[major_findings_from_investigation][{{ $index }}][notes]" placeholder="Notes">{{ $row['notes'] }}</textarea></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Explanation of Imbalances</label>
                    <textarea class="border rounded px-3 py-2 w-full" rows="3" name="ayurvedic_summary[explanation_of_imbalances]" placeholder="Explanation of imbalances">{{ $oldOrPayload('ayurvedic_summary.explanation_of_imbalances') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Samprapti / Pathogenesis</label>
                    <textarea class="border rounded px-3 py-2 w-full" rows="4" name="ayurvedic_summary[samprapti_pathogenesis]" placeholder="Samprapti / Pathogenesis">{{ $oldOrPayload('ayurvedic_summary.samprapti_pathogenesis') }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-3">
                    <input class="border rounded px-3 py-2" type="text" name="ayurvedic_summary[diagnosis_prognosis][diagnosis]" value="{{ $oldOrPayload('ayurvedic_summary.diagnosis_prognosis.diagnosis') }}" placeholder="Diagnosis">
                    <input class="border rounded px-3 py-2" type="text" name="ayurvedic_summary[diagnosis_prognosis][rogi_bala]" value="{{ $oldOrPayload('ayurvedic_summary.diagnosis_prognosis.rogi_bala') }}" placeholder="Rogi Bala">
                    <input class="border rounded px-3 py-2" type="text" name="ayurvedic_summary[diagnosis_prognosis][roga_bala]" value="{{ $oldOrPayload('ayurvedic_summary.diagnosis_prognosis.roga_bala') }}" placeholder="Roga Bala">
                    <input class="border rounded px-3 py-2" type="text" name="ayurvedic_summary[diagnosis_prognosis][prognosis]" value="{{ $oldOrPayload('ayurvedic_summary.diagnosis_prognosis.prognosis') }}" placeholder="Prognosis">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Objectives to Achieve</label>
                    <textarea class="border rounded px-3 py-2 w-full" rows="3" name="ayurvedic_summary[objectives_to_achieve]" placeholder="Objectives to achieve">{{ $oldOrPayload('ayurvedic_summary.objectives_to_achieve') }}</textarea>
                </div>
            </div>
        </section>
                </div>
                <div data-tab-panel="plan" class="consultation-tab-panel space-y-6" id="tab-plan">

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
            <h2 class="text-lg font-semibold text-secondary mb-3">Treatment Plan</h2>
            @php
                $internalMedicines = old('treatment_plan.internal_medicines', data_get($payload, 'treatment_plan.internal_medicines', []));
                $internalMedicines = is_array($internalMedicines) ? array_values($internalMedicines) : [];
                if (!count($internalMedicines)) {
                    $internalMedicines[] = ['medicine_name' => '', 'form' => '', 'dose' => '', 'timing' => '', 'duration' => '', 'indications' => ''];
                }
            @endphp
            <div class="space-y-6">
                <div>
                    <h3 class="font-medium mb-2">Internal Medicines</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left p-2">Medicine Name</th>
                                <th class="text-left p-2">Form</th>
                                <th class="text-left p-2">Dose</th>
                                <th class="text-left p-2">Timing</th>
                                <th class="text-left p-2">Duration</th>
                                <th class="text-left p-2">Indications</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($internalMedicines as $index => $row)
                            <tr>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="treatment_plan[internal_medicines][{{ $index }}][medicine_name]" value="{{ $row['medicine_name'] }}" placeholder="Medicine name"></td>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="treatment_plan[internal_medicines][{{ $index }}][form]" value="{{ $row['form'] }}" placeholder="Form"></td>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="treatment_plan[internal_medicines][{{ $index }}][dose]" value="{{ $row['dose'] }}" placeholder="Dose"></td>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="treatment_plan[internal_medicines][{{ $index }}][timing]" value="{{ $row['timing'] }}" placeholder="Timing"></td>
                                <td class="p-2"><input class="w-full border rounded px-3 py-2" name="treatment_plan[internal_medicines][{{ $index }}][duration]" value="{{ $row['duration'] }}" placeholder="Duration"></td>
                                <td class="p-2"><textarea class="w-full border rounded px-3 py-2" rows="2" name="treatment_plan[internal_medicines][{{ $index }}][indications]" placeholder="Indications">{{ $row['indications'] }}</textarea></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="grid md:grid-cols-3 gap-3">
                    <div>
                        <h3 class="font-medium mb-2">External Therapies</h3>
                        <textarea class="border rounded px-3 py-2 w-full" rows="4" name="treatment_plan[external_therapies_notes]" placeholder="Therapy name, type, medium used, duration, frequency, indications">{{ $oldOrPayload('treatment_plan.external_therapies_notes') }}</textarea>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">Self-Care Therapies</h3>
                        <textarea class="border rounded px-3 py-2 w-full" rows="4" name="treatment_plan[self_care_therapies_notes]" placeholder="Therapy name, type, medium used, duration, frequency, indications">{{ $oldOrPayload('treatment_plan.self_care_therapies_notes') }}</textarea>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">Prescribed Practices</h3>
                        <textarea class="border rounded px-3 py-2 w-full" rows="4" name="treatment_plan[prescribed_practices_notes]" placeholder="Practice, type, duration, frequency, timing, indications">{{ $oldOrPayload('treatment_plan.prescribed_practices_notes') }}</textarea>
                    </div>
                </div>
            </div>
        </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
            <h2 class="text-lg font-semibold text-secondary mb-3">Lifestyle and Dietary Advice</h2>
            <div class="grid md:grid-cols-3 gap-3">
                <textarea class="border rounded px-3 py-2" rows="3" name="lifestyle_dietary_advice[pathya]" placeholder="Pathya">{{ $oldOrPayload('lifestyle_dietary_advice.pathya') }}</textarea>
                <textarea class="border rounded px-3 py-2" rows="3" name="lifestyle_dietary_advice[apathya]" placeholder="Apathya">{{ $oldOrPayload('lifestyle_dietary_advice.apathya') }}</textarea>
                <textarea class="border rounded px-3 py-2" rows="3" name="lifestyle_dietary_advice[daily_routine_suggestions]" placeholder="Daily routine suggestions">{{ $oldOrPayload('lifestyle_dietary_advice.daily_routine_suggestions') }}</textarea>
            </div>
        </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
            <h2 class="text-lg font-semibold text-secondary mb-3">Follow-Up Plan</h2>
            <div class="grid md:grid-cols-3 gap-3">
                <input class="border rounded px-3 py-2" type="text" name="follow_up_plan[next_review_dates]" value="{{ $oldOrPayload('follow_up_plan.next_review_dates') }}" placeholder="Next review date(s)">
                <textarea class="border rounded px-3 py-2" rows="3" name="follow_up_plan[therapy_adjustments]" placeholder="Therapy adjustments">{{ $oldOrPayload('follow_up_plan.therapy_adjustments') }}</textarea>
                <textarea class="border rounded px-3 py-2" rows="3" name="follow_up_plan[expected_outcomes]" placeholder="Expected outcomes">{{ $oldOrPayload('follow_up_plan.expected_outcomes') }}</textarea>
            </div>
        </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-3">
            <h2 class="text-lg font-semibold text-secondary mb-3">Treating Consultant</h2>
            <div class="grid md:grid-cols-2 gap-3">
                <input class="border rounded px-3 py-2" type="text" name="treating_consultant[name]" value="{{ $oldOrPayload('treating_consultant.name', $user->name ?? '') }}" placeholder="Name">
                <input class="border rounded px-3 py-2" type="text" name="treating_consultant[tcmc_reg_no]" value="{{ $oldOrPayload('treating_consultant.tcmc_reg_no') }}" placeholder="TCMC Reg No.">
                <input class="border rounded px-3 py-2" type="text" name="treating_consultant[zaya_unique_id_no]" value="{{ $oldOrPayload('treating_consultant.zaya_unique_id_no') }}" placeholder="Zaya Unique ID No.">
                <input class="border rounded px-3 py-2" type="text" name="treating_consultant[speciality]" value="{{ $oldOrPayload('treating_consultant.speciality') }}" placeholder="Speciality">
                <textarea class="border rounded px-3 py-2 md:col-span-2" rows="3" name="treating_consultant[signature]" placeholder="Signature">{{ $oldOrPayload('treating_consultant.signature') }}</textarea>
            </div>
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
    const form = document.getElementById('doctor-consultation-form');
    if (!form) {
        return;
    }

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
            const section = addButton.getAttribute('data-repeat-add');
            addRow(section);
            return;
        }

        const removeButton = event.target.closest('[data-repeat-remove]');
        if (removeButton) {
            event.preventDefault();
            const section = removeButton.getAttribute('data-repeat-remove');
            const row = removeButton.closest('[data-repeat-row]');
            if (row) {
                row.remove();
                counters[section] = Math.max((counters[section] ?? 1) - 1, 0);
            }
        }
    });
})();
</script>
@endpush
