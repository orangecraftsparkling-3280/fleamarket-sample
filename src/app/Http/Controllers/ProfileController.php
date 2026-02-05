<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();

        $user->load('profile');

        return view('profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'name' => $request->name,
        ]);

        $profileData = [
            'post_code' => $request->post_code,
            'address'   => $request->address,
            'building'  => $request->building,
        ];


        if ($request->hasFile('img_path')) {
            if ($user->profile && $user->profile->img_path) {
                Storage::disk('public')->delete($user->profile->img_path);
            }

            $path = $request->file('img_path')->store('profiles', 'public');
            $profileData['img_path'] = $path;
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect('/');
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('profile');

        if ($request->tab === 'buy') {
            $items = $user->boughtItems()->get();
        } else {
            $items = $user->items()->get();
        }

        return view('mypage', compact('user', 'items'));
    }
}
