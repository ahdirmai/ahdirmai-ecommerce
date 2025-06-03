<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    public function index()
    {
        return view('user.profile.index');
    }
    public function edit()
    {
        return view('user.profile.edit');
    }

    public function updateInfo(Request $request)
    {
        // Validate input including avatar
        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'sometimes|string|email|max:255|unique:users,email,' . auth()->id(),
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $user->name = $request->input('name');
            // Only update email if present in the request
            // if ($request->has('email')) {
            //     $user->email = $request->input('email');
            // }
            $user->save();

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $user->clearMediaCollection('avatars');
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }

            DB::commit();
            Alert::success('Success', 'Profile information updated successfully.');
            return redirect()->route('profile.show');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update profile.']);
        }
    }
}
