<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Practitioner;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PractitionerController extends Controller
{
    #[OA\Get(
        path: '/practitioners',
        summary: 'Get all practitioners',
        description: 'Returns a list of all practitioners with their profile details and qualifications.',
        tags: ['Practitioners'],
        security: [['apiKeyAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object'))
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Server error')
        ]
    )]
    public function index()
    {
        try {
            $practitioners = User::where('role', 'practitioner')
                ->with(['practitioner.qualifications'])
                ->get()
                ->map(function ($user) {
                    $profile = $user->practitioner;
                    if (!$profile) return null;

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $profile->phone ?? null,
                        'gender' => $profile->gender ?? null,
                        'dob' => $profile->dob ?? null,
                        'nationality' => $profile->nationality ?? null,
                        'address' => [
                            'line_1' => $profile->address_line_1 ?? null,
                            'line_2' => $profile->address_line_2 ?? null,
                            'city' => $profile->city ?? null,
                            'state' => $profile->state ?? null,
                            'zip_code' => $profile->zip_code ?? null,
                            'country' => $profile->country ?? null,
                        ],
                        'profile_photo' => $profile->profile_photo_path ? asset('storage/' . $profile->profile_photo_path) : null,
                        'bio' => $profile->profile_bio ?? null,
                        'website' => $profile->website_url ?? null,
                        'social_links' => $profile->social_links ?? [],
                        'consultations' => $profile->consultations ?? [],
                        'body_therapies' => $profile->body_therapies ?? [],
                        'other_modalities' => $profile->other_modalities ?? [],
                        'languages' => $profile->languages_spoken ?? [],
                        'can_translate_english' => (bool) ($profile->can_translate_english ?? false),
                        'qualifications' => $profile->qualifications ?? [],
                        'documents' => [
                            'cover_letter' => $profile->doc_cover_letter ? asset('storage/' . $profile->doc_cover_letter) : null,
                            'certificates' => $profile->doc_certificates ? asset('storage/' . $profile->doc_certificates) : null,
                            'experience' => $profile->doc_experience ? asset('storage/' . $profile->doc_experience) : null,
                            'registration' => $profile->doc_registration ? asset('storage/' . $profile->doc_registration) : null,
                            'ethics' => $profile->doc_ethics ? asset('storage/' . $profile->doc_ethics) : null,
                            'contract' => $profile->doc_contract ? asset('storage/' . $profile->doc_contract) : null,
                            'id_proof' => $profile->doc_id_proof ? asset('storage/' . $profile->doc_id_proof) : null,
                        ],
                        'status' => $profile->status ?? 'pending',
                        'created_at' => $user->created_at,
                    ];
                })->filter();

            return response()->json([
                'success' => true,
                'data' => $practitioners->values()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching practitioners: ' . $e->getMessage()
            ], 500);
        }
    }
}
