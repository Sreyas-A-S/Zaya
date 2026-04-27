<?php

namespace App\Models;

use App\Models\Country;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Traits\HasProfileCompletion;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasProfileCompletion;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'email',
        'password',
        'role',
        'national_id',
        'languages',
        'status',
        'profile_pic',
        'phone',
        'promo_code',
        'referral_token',
        'referred_by',
        'open_register_link_id',
        'google_id',
        'facebook_id',
        'apple_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'national_id' => 'array',
            'languages' => 'array',
        ];
    }
    /**
     * Get the specific profile model based on the user's role.
     */
    public function getProfileAttribute()
    {
        return match ($this->role) {
            'client', 'patient' => $this->patient,
            'practitioner' => $this->practitioner,
            'doctor' => $this->doctor,
            'mindfulness_practitioner', 'mindfulness-practitioner' => $this->mindfulnessPractitioner,
            'yoga_therapist', 'yoga-therapist' => $this->yogaTherapist,
            'translator' => $this->translator,
            default => null,
        };
    }

    /**
     * Get the ID of the related profile.
     */
    public function getProfileIdAttribute()
    {
        $profile = $this->profile;
        return $profile ? $profile->id : null;
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    public function practitioner()
    {
        return $this->hasOne(Practitioner::class);
    }
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
    public function mindfulnessPractitioner()
    {
        return $this->hasOne(MindfulnessPractitioner::class);
    }
    public function translator()
    {
        return $this->hasOne(Translator::class);
    }
    public function yogaTherapist()
    {
        return $this->hasOne(YogaTherapist::class);
    }

    public function gallery()
    {
        return $this->hasMany(\App\Models\PractitionerGallery::class, 'user_id');
    }

    public function userServices()
    {
        return $this->hasMany(UserService::class);
    }

    public function clinicalDocuments()
    {
        return $this->hasMany(ClinicalDocument::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'national_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'languages'); // Note: if 'languages' is a single ID, if it's JSON it might need decoding
    }

    public function nationality()
    {
        return $this->belongsTo(Country::class, 'national_id');
    }

    /**
     * Get the role associated with the user based on the 'role' string.
     * Note: This assumes users.role matches roles.name (case sensitive or needs mapping).
     */
    public function roleData()
    {
        $roleName = $this->role;
        
        $mapping = [
            'super-admin' => 'Super Admin',
            'admin' => 'Admin',
            'country-admin' => 'Country Admin',
            'financial-manager' => 'Financial Manager',
            'content-manager' => 'Content Manager',
            'user-manager' => 'User Manager',
        ];

        if (isset($mapping[$roleName])) {
            $roleName = $mapping[$roleName];
        }

        return Role::where('name', $roleName)->first();
    }

    public function hasPermission($permissionSlug)
    {
        $role = $this->roleData();
        if (!$role) return false;

        // Super Admin has all permissions
        if ($role->name === 'Super Admin') return true;

        return $role->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Returns a human-friendly reason why this user should be blocked from logging in,
     * or null if login is allowed.
     */
    public function getLoginBlockReason(): ?string
    {
        // Super admin is never blocked due to status
        if ($this->role === 'super-admin') {
            return null;
        }

        $userStatus = strtolower(trim((string) ($this->status ?? 'active')));
        if ($userStatus !== 'active') {
            return 'Your account is currently inactive. Please wait for approval or contact support.';
        }

        $profile = $this->profile;
        if ($profile && isset($profile->status)) {
            $profileStatus = strtolower(trim((string) ($profile->status ?? 'active')));
            if ($profileStatus !== 'active') {
                return 'Your account is currently inactive. Please wait for approval or contact support.';
            }
        }

        return null;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the public profile URL for this user.
     */
    public function getProfileUrlAttribute()
    {
        $profile = $this->profile;
        if ($profile && isset($profile->slug)) {
            return route('practitioner-detail', $profile->slug);
        }
        
        // Fallback or ID-based if we implement it in controller
        if ($profile) {
            return route('practitioner-detail', $profile->id);
        }

        return url('/dashboard');
    }

    public function getProfilePicUrlAttribute()
    {
        // 1. Check direct profile_pic on User
        $pic = $this->profile_pic;
        
        // 2. Check for role-specific photo path attributes that might be on the model (e.g. from joins)
        if (!$pic) {
            if (isset($this->profile_photo_path) && $this->profile_photo_path) {
                $pic = $this->profile_photo_path;
            } elseif (isset($this->dr_profile_pic) && $this->dr_profile_pic) {
                $pic = $this->dr_profile_pic;
            }
        }

        // 3. Fallback to relationship if still not found
        if (!$pic) {
            $profile = $this->profile;
            if ($profile) {
                if (isset($profile->profile_photo_path) && $profile->profile_photo_path) {
                    $pic = $profile->profile_photo_path;
                } elseif (isset($profile->profile_pic) && $profile->profile_pic) {
                    $pic = $profile->profile_pic;
                }
            }
        }

        if ($pic) {
            if (str_starts_with($pic, 'http')) {
                return $pic;
            }
            return asset('storage/' . $pic);
        }

        // Return a consistent placeholder based on role or a safe default
        $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist', 'translator'];
        if ($this->role && in_array($this->role, $practitionerRoles)) {
            return asset('frontend/assets/lilly-profile-pic.png');
        }

        return asset('frontend/assets/profile-dummy-img.png');
        }

    public function practitionerTransactions()
    {
        return $this->hasMany(Transaction::class, 'practitioner_id');
    }

    public function referrerTransactions()
    {
        return $this->hasMany(Transaction::class, 'referrer_id');
    }

    public function userPromoCodes()
    {
        return $this->hasMany(UserPromoCode::class);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function generateReferralToken()
    {
        do {
            $token = \Illuminate\Support\Str::random(10);
        } while (self::where('referral_token', $token)->exists());

        $this->update(['referral_token' => $token]);
        return $token;
    }

    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }
}
