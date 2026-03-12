<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Practitioner;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Package;
use App\Models\Language;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

use App\Traits\AdminFilterTrait;

class AdminController extends Controller
{
    use AdminFilterTrait;

    public function __construct()
    {
        $this->middleware('permission:dashboard-view')->only(['index']);
    }

    public function index()
    {
        $user = Auth::user();
        $user->load('nationality');

        $myLanguages = [];
        if ($user->languages) {
            $langIds = is_array($user->languages) ? $user->languages : json_decode($user->languages, true);
            if ($langIds) {
                $myLanguages = Language::whereIn('id', $langIds)->get();
            }
        }

        $stats = [
            'total_users' => $this->applyAdminFilters(User::query(), 'user')->count(),
            'total_practitioners' => $this->applyAdminFilters(Practitioner::query(), 'user')->count(),
            'total_patients' => $this->applyAdminFilters(Patient::query(), 'user')->count(),
            'total_services' => Service::count(),
            'total_packages' => Package::count(),
        ];

        return view('admin.dashboard', compact('user', 'myLanguages', 'stats'));
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load('nationality');

        $myLanguages = [];
        if ($user->languages) {
            $langIds = is_array($user->languages) ? $user->languages : json_decode($user->languages, true);
            if ($langIds) {
                $myLanguages = Language::whereIn('id', $langIds)->get();
            }
        }

        $countries = Country::all();

        return view('admin.profile', compact('user', 'myLanguages', 'countries'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|min:2|max:50|regex:/^[a-zA-Z\s\-]+$/',
            'last_name' => 'required|string|min:2|max:50|regex:/^[a-zA-Z\s\-]+$/',
            'email' => 'required|email:rfc,dns|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|min:7|max:20',
            'national_id' => 'nullable|exists:countries,id',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cropped_image' => 'nullable|string',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, and hyphens.',
            'last_name.regex' => 'Last name can only contain letters, spaces, and hyphens.',
            'national_id.exists' => 'Selected nationality is invalid.',
        ]);

        $data = $request->only(['first_name', 'last_name', 'email', 'phone', 'national_id']);
        $data['name'] = trim($request->first_name . ' ' . $request->last_name);

        if ($request->filled('cropped_image')) {
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }
            $data['profile_pic'] = $this->uploadBase64($request->cropped_image);
        } elseif ($request->hasFile('profile_pic')) {
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $data['profile_pic'] = $path;
        }

        $user->update($data);

        return back()->with('status', 'Profile updated successfully!');
    }

    /**
     * Helper to upload base64 image
     */
    protected function uploadBase64($base64String)
    {
        $image_parts = explode(";base64,", $base64String);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'profile_pics/' . uniqid() . '.' . $image_type;

        Storage::disk('public')->put($fileName, $image_base64);

        return $fileName;
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Password updated successfully!');
    }
}
