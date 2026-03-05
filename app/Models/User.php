<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
        'email',
        'password',
        'role',
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
        ];
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
}
