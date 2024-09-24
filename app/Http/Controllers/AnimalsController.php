<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use DateTime;

class AnimalsController extends Controller
{
    public function index(): Response
    {
        $animals = Animal::join('clients', 'clients.id', '=', 'animals.client_id')
            ->join('users', 'users.id', '=', 'clients.user_id')
            ->where('users.id', Auth::user()->id)
            ->select('animals.*')
            ->get();
    
        foreach ($animals as $animal) {
            $animal->birth_date_formated       = (new DateTime($animal->birth_date))->format('d/m/Y');
            $animal->sex_formated              = $animal->sex == 0 ? 'Macho' : 'Fêmea';
            $animal->castrated_animal_formated = $animal->castrated_animal == 0 ? 'Não' : 'Sim';
            $animal->client_name               = Client::whereId($animal->client_id)->first()->name;
            $animal->json_data                 = $animal->toJson();
        }
    
        return Inertia::render('Animals/Index', [
            'animals' => $animals,
            'clients' => Client::get(['id', 'name']),
        ]);
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'name'             => ['required', 'max:255'],
            'species'          => ['required', 'max:255'],
            'race'             => ['required', 'max:255'],
            'sex'              => ['required', 'max:255'],
            'castrated_animal' => ['required', 'max:255'],
            'birth_date'       => ['required', 'date'],
            'client_id'        => ['required', 'integer'],
        ]);

        $animal = new Animal();
        $animal->name             = Request::get('name');
        $animal->species          = Request::get('species');
        $animal->race             = Request::get('race');
        $animal->sex              = Request::get('sex');
        $animal->castrated_animal = Request::get('castrated_animal');
        $animal->birth_date       = Request::get('birth_date');
        $animal->client_id        = Request::get('client_id');
        $animal->save();

        return Redirect::route('animals')->with('success', 'O animal foi cadastrado com sucesso.');
    }

    public function update(): RedirectResponse
    {
        Request::validate([
            'name'             => ['required', 'max:255'],
            'species'          => ['required', 'max:255'],
            'race'             => ['required', 'max:255'],
            'sex'              => ['required', 'max:255'],
            'castrated_animal' => ['required', 'max:255'],
            'birth_date'       => ['required', 'date'],
            'client_id'        => ['required', 'integer'],
        ]);

        $animal = Animal::whereId(Request::get('id'))->first();
        $animal->name             = Request::get('name');
        $animal->species          = Request::get('species');
        $animal->race             = Request::get('race');
        $animal->sex              = Request::get('sex');
        $animal->castrated_animal = Request::get('castrated_animal');
        $animal->birth_date       = Request::get('birth_date');
        $animal->client_id        = Request::get('client_id');
        $animal->save();

        return Redirect::route('animals')->with('success', 'O animal foi atualizado com sucesso.');
    }

    public function destroy(): RedirectResponse
    {
        $animal = Animal::whereId(Request::get('id'))->first();
        $animal->delete();

        return Redirect::back()->with('success', 'O animal foi deletado com sucesso.');
    }
}
