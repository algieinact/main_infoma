<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseController
{
    /**
     * Display the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return $this->sendResponse($request->user(), 'User profile retrieved successfully');
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:255',
            'birth_date' => 'sometimes|required|date',
            'gender' => 'sometimes|required|in:male,female',
            'university' => 'sometimes|required|string|max:255',
            'major' => 'sometimes|required|string|max:255',
            'graduation_year' => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 4),
            'current_password' => 'required_with:new_password|string',
            'new_password' => 'sometimes|required|string|min:8|confirmed',
            'avatar' => 'sometimes|required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Verify current password if changing password
        if ($request->has('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return $this->sendError('Current password is incorrect', [], 422);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Update other fields
        $user->fill($request->only([
            'name',
            'email',
            'phone',
            'address',
            'birth_date',
            'gender',
            'university',
            'major',
            'graduation_year',
        ]));

        $user->save();

        return $this->sendResponse($user, 'User profile updated successfully');
    }
} 