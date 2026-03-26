<?php

use App\Models\Booking;
use App\Models\ClinicalDocument;
use App\Models\Patient;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->longText('payment_details')->nullable()->change();
        });

        Schema::table('clinical_documents', function (Blueprint $table) {
            $table->text('file_name')->change();
        });

        $this->encryptBookings();
        $this->encryptPatients();
        $this->encryptClinicalDocuments();
    }

    public function down(): void
    {
        // Data cannot be safely restored to plaintext.
    }

    private function encryptBookings(): void
    {
        DB::table('bookings')
            ->select('id', 'conditions', 'situation', 'payment_details')
            ->orderBy('id')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    $updates = [];

                    if (!empty($row->conditions) && ! $this->isEncrypted($row->conditions)) {
                        $booking = new Booking();
                        $booking->conditions = $row->conditions;
                        $updates['conditions'] = $booking->getAttributes()['conditions'];
                    }

                    if (!empty($row->situation) && ! $this->isEncrypted($row->situation)) {
                        $booking = new Booking();
                        $booking->situation = $row->situation;
                        $updates['situation'] = $booking->getAttributes()['situation'];
                    }

                    if (!empty($row->payment_details) && ! $this->isEncrypted($row->payment_details)) {
                        $decoded = json_decode($row->payment_details, true);

                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $booking = new Booking();
                            $booking->payment_details = $decoded;
                            $updates['payment_details'] = $booking->getAttributes()['payment_details'];
                        }
                    }

                    if ($updates !== []) {
                        DB::table('bookings')->where('id', $row->id)->update($updates);
                    }
                }
            }, 'id');
    }

    private function encryptPatients(): void
    {
        DB::table('patients')
            ->select('id', 'consultation_preferences')
            ->whereNotNull('consultation_preferences')
            ->orderBy('id')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    if ($this->isEncrypted($row->consultation_preferences)) {
                        continue;
                    }

                    $decoded = json_decode($row->consultation_preferences, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        continue;
                    }

                    $patient = new Patient();
                    $patient->consultation_preferences = $decoded;

                    DB::table('patients')
                        ->where('id', $row->id)
                        ->update([
                            'consultation_preferences' => $patient->getAttributes()['consultation_preferences'],
                        ]);
                }
            }, 'id');
    }

    private function encryptClinicalDocuments(): void
    {
        DB::table('clinical_documents')
            ->select('id', 'file_name')
            ->whereNotNull('file_name')
            ->orderBy('id')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    if ($this->isEncrypted($row->file_name)) {
                        continue;
                    }

                    $document = new ClinicalDocument();
                    $document->file_name = $row->file_name;

                    DB::table('clinical_documents')
                        ->where('id', $row->id)
                        ->update([
                            'file_name' => $document->getAttributes()['file_name'],
                        ]);
                }
            }, 'id');
    }

    private function isEncrypted(?string $value): bool
    {
        if (! is_string($value) || $value === '') {
            return false;
        }

        $decoded = base64_decode($value, true);

        if ($decoded === false) {
            return false;
        }

        $payload = json_decode($decoded, true);

        return is_array($payload)
            && isset($payload['iv'], $payload['value'], $payload['mac']);
    }
};
