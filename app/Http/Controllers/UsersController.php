<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'full_name' => ['required', 'min:3', 'max:25'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['required', 'min:3', 'max:255'],
            'confirm_password' => ['same:password'],
        ]);

        $user = new User();
        $user->full_name = Request::get('full_name');
        $user->email = Request::get('email');
        $user->password = bcrypt(Request::get('password'));
        $user->save();

        return Redirect::route('user.create')->with('success', 'Usuário cadastrado com sucesso.');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }
}
