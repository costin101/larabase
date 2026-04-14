<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserValidateAction;
use App\Actions\User\CreateUserAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register',[
            'countries' => \App\Models\Country::all(['id', 'name', 'iso_alpha2','phone_prefix']),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, RegisterUserValidateAction $registerUserValidateAction, CreateUserAction $createUserAction): RedirectResponse
    {
        $registerUserValidateAction->execute($request);

        $user = $createUserAction->execute($request->all());

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
