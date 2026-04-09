<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    //
    public function users(): Response
    {
        $users = User::paginate(1);

        return Inertia::render('Users/Users',[
            'users' => $users
        ]);
    }

    public function show($id): Response
    {
        $user = User::findCachable($id);
        return Inertia::render('User/User',[
            'user' => $user
        ]);
    }

    public function edit($id): Response
    {
        $user = User::findCachable($id);
        return Inertia::render('User/EditUser',[
            'user' => $user
        ]);
    }

    public function updatePassword($id, Request $request) {
        $validData = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = User::findCachable($id);
        $user->update([
            'password' => Hash::make($validData['password']),
        ]);

        return back();
    }

    public function update($id, UserProfileUpdateRequest $request): RedirectResponse
    {
        $validData = $request->validated();
        $user = User::findCachable($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        return Redirect::route('user.edit', $id);
    }
}
