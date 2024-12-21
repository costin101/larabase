<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
}
