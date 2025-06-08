<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Animal;
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
use App\Http\Controllers\NotificationsController;


class ClientsController extends Controller
{
    public function index(): Response
    {
        $clients = Client::where('user_id', Auth::id())->get();

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

        $notificacoes = (new NotificationsController())->index()->getData();

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
            'animals' => $animals,
            'notificacoes' => $notificacoes,
        ]);
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'name'         => ['required', 'max:255'],
            'email'        => ['required', 'max:255', 'email', Rule::unique('clients')],
            'cpf'          => ['required', 'digits:11'],
            'phone'        => ['required', 'digits:11'],
            'cep'          => ['required', 'max:255'],
            'street'       => ['required', 'max:255'],
            'number'       => ['required', 'max:255'],
            'complement'   => ['required', 'max:255'],
            'neighborhood' => ['required', 'max:255'],
            'city'         => ['required', 'max:255'],
            'state'        => ['required', 'max:255'],
        ]);

        $client = new Client();
        $client->name         = Request::get('name');
        $client->email        = Request::get('email');
        $client->cpf          = Request::get('cpf');
        $client->phone        = Request::get('phone');
        $client->cep          = Request::get('cep');
        $client->street       = Request::get('street');
        $client->number       = Request::get('number');
        $client->complement   = Request::get('complement');
        $client->neighborhood = Request::get('neighborhood');
        $client->city         = Request::get('city');
        $client->state        = Request::get('state');
        $client->user_id      = Auth::id();
        $client->save();

        return Redirect::route('clients')->with('success', 'O cliente foi cadastrado com sucesso.');
    }

    public function update(): RedirectResponse
    {
        Request::validate([
            'name'         => ['required', 'max:255'],
            'email'        => ['required', 'max:255', 'email', Rule::unique('clients')->ignore(Request::get('id'))],
            'cpf'          => ['required', 'digits:11'],
            'phone'        => ['required', 'digits:11'],
            'cep'          => ['required', 'max:255'],
            'street'       => ['required', 'max:255'],
            'number'       => ['required', 'max:255'],
            'complement'   => ['required', 'max:255'],
            'neighborhood' => ['required', 'max:255'],
            'city'         => ['required', 'max:255'],
            'state'        => ['required', 'max:255'],
        ]);

        $client = Client::whereId(Request::get('id'))->first();
        $client->name         = Request::get('name');
        $client->email        = Request::get('email');
        $client->cpf          = Request::get('cpf');
        $client->phone        = Request::get('phone');
        $client->cep          = Request::get('cep');
        $client->street       = Request::get('street');
        $client->number       = Request::get('number');
        $client->complement   = Request::get('complement');
        $client->neighborhood = Request::get('neighborhood');
        $client->city         = Request::get('city');
        $client->state        = Request::get('state');
        $client->save();

        return Redirect::back()->with('success', 'O cliente foi atualizado com sucesso.');
    }

    public function destroy(): RedirectResponse
    {
        $client = Client::whereId(Request::get('id'))->first();
        $client->delete();

        return Redirect::back()->with('success', 'O cliente foi deletado com sucesso.');
    }
}
