<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Menampilkan halaman profil
        return view('profile.index');
    }

    public function edit()
    {
        // Menampilkan form edit profil
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        // Validasi dan update data profil
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1900|max:' . (date('Y')+10),
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatar;
        }

        // Update all fields
        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->birth_date = $validated['birth_date'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        $user->university = $validated['university'] ?? null;
        $user->major = $validated['major'] ?? null;
        $user->graduation_year = $validated['graduation_year'] ?? null;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}