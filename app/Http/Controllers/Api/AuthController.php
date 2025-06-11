<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class AuthController extends BaseController
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->sendError('Login Failed', [
                'email' => ['Email atau password yang Anda masukkan salah'],
                'password' => ['Email atau password yang Anda masukkan salah']
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        return $this->sendResponse($data, 'Login berhasil');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,provider',
            'phone' => 'nullable|string|max:255',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'university' => $request->university,
            'major' => $request->major,
            'graduation_year' => $request->graduation_year,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        return $this->sendResponse($data, 'Registrasi berhasil', 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse(null, 'Logout berhasil');
    }

    public function me(Request $request)
    {
        return $this->sendResponse($request->user(), 'Data user berhasil diambil');
    }

    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role !== 'provider') {
            abort(403, 'Unauthorized action.');
        }
        $residences = $user->residences()->with('category')->get();
        $activities = $user->activities()->with('category')->get();
        return view('provider.dashboard', compact('residences', 'activities'));
    }
}