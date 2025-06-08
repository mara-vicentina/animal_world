<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Animal;
use App\Models\Financial;
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

class AppointmentsController extends Controller
{
    public function index(): Response
    {
        $appointments = Appointment::join('animals', 'animals.id', '=', 'appointments.animal_id')
        ->join('clients', 'clients.id', '=', 'animals.client_id')
        ->join('users', 'users.id', '=', 'clients.user_id')
        ->where('users.id', Auth::user()->id)
        ->select('appointments.*')
        ->get();
    
        foreach ($appointments as $appointment) {
            $appointment->reason_formated           = $appointment->reason == 0 ? 'Vacinação' : ($appointment->reason == 1 ? 'Rotina' : 'Análise Clínica');
            $appointment->animal_name               = Animal::whereId($appointment->animal_id)->first()->name;
            $animal                                 = Animal::whereId($appointment->animal_id)->first();
            $appointment->client_name               = $animal ? Client::whereId($animal->client_id)->first()->name : 'Cliente não encontrado';
            $appointment->client_id                 = $animal ? Client::whereId($animal->client_id)->first()->id : 'Cliente não encontrado';
            $appointment->json_data                 = $appointment->toJson();
        }
    
        $notificacoes = (new NotificationsController())->index()->getData();

        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments,
            'clients'      => Client::get(['id', 'name']),
            'animals'      => Animal::get(['id', 'name', 'client_id']),
            'notificacoes' => $notificacoes,
        ]);
    }

    public function store(): RedirectResponse
    {
        $data = Request::validate([
            'reason'    => ['required', 'integer', 'between:0,2'],
            'animal_id' => ['required', 'integer'],
            'value'     => ['required', 'decimal:2'],
        ]);
    
        if ($data['reason'] == 0) {
            Request::validate([
                'which_vaccines' => ['required', 'max:255'],
            ]);
        }  elseif ($data['reason'] == 1) {
            Request::validate([
                'observations_description'     => ['required', 'max:255'],
                'up_to_date_vaccines'          => ['required', 'max:255'],
                'up_to_date_deworming'         => ['required', 'max:255'],
                'use_antiparasitics'           => ['required', 'max:255'],
                'previous_surgeries'           => ['required', 'max:255'],
                'illnesses_chronic_conditions' => ['required', 'max:255'],
                'medication'                   => ['required', 'max:255'],
                'exams'                        => ['required', 'max:255'],
            ]);
        } elseif ($data['reason'] == 2) {
            Request::validate([
                'up_to_date_vaccines'          => ['required', 'max:255'],
                'up_to_date_deworming'         => ['required', 'max:255'],
                'use_antiparasitics'           => ['required', 'max:255'],
                'previous_surgeries'           => ['required', 'max:255'],
                'illnesses_chronic_conditions' => ['required', 'max:255'],
                'medication'                   => ['required', 'max:255'],
                'main_symptoms'                => ['required', 'max:255'],
                'change_behavior'              => ['required', 'max:255'],
                'temperature'                  => ['required', 'max:255'],
                'heart_respiratory_rate'       => ['required', 'max:255'],
                'mucous_membranes_hydration'   => ['required', 'max:255'],
                'weight'                       => ['required', 'max:255'],
                'pain_scale'                   => ['required', 'max:255'],
                'skin_coat'                    => ['required', 'max:255'],
                'eyes_ears'                    => ['required', 'max:255'],
                'oral_cavity'                  => ['required', 'max:255'],
                'abdomen_locomotion'           => ['required', 'max:255'],
                'reproductive_system'          => ['required', 'max:255'],
                'exams'                        => ['required', 'max:255'],
            ]);
        }

        $appointment = new Appointment();
        $appointment->reason                        = Request::get('reason');
        $appointment->value                         = Request::get('value');
        $appointment->which_vaccines                = Request::get('which_vaccines');
        $appointment->up_to_date_vaccines           = Request::get('up_to_date_vaccines');
        $appointment->up_to_date_deworming          = Request::get('up_to_date_deworming');
        $appointment->use_antiparasitics            = Request::get('use_antiparasitics');
        $appointment->previous_surgeries            = Request::get('previous_surgeries');
        $appointment->illnesses_chronic_conditions  = Request::get('illnesses_chronic_conditions');
        $appointment->medication                    = Request::get('medication');
        $appointment->main_symptoms                 = Request::get('main_symptoms');
        $appointment->change_behavior               = Request::get('change_behavior');
        $appointment->temperature                   = Request::get('temperature');
        $appointment->heart_respiratory_rate        = Request::get('heart_respiratory_rate');
        $appointment->mucous_membranes_hydration    = Request::get('mucous_membranes_hydration');
        $appointment->weight                        = Request::get('weight');
        $appointment->pain_scale                    = Request::get('pain_scale');
        $appointment->skin_coat                     = Request::get('skin_coat');
        $appointment->eyes_ears                     = Request::get('eyes_ears');
        $appointment->oral_cavity                   = Request::get('oral_cavity');
        $appointment->abdomen_locomotion            = Request::get('abdomen_locomotion');
        $appointment->reproductive_system           = Request::get('reproductive_system');
        $appointment->exams                         = Request::get('exams');
        $appointment->observations_description      = Request::get('observations_description');
        $appointment->animal_id                     = Request::get('animal_id');
        $appointment->save();

        $animal = Animal::whereId($appointment->animal_id)->first();
        $client = Client::whereId($animal->client_id)->first();

        $financial = new Financial();
        $financial->name       = "Entrada da consulta referente ao animal {$animal->name} e o cliente {$client->name}";
        $financial->type       = 1;
        $financial->value      = Request::get('value');
        $financial->date       = new DateTime();
        $financial->user_id    = Auth::id();
        $financial->save();

        return Redirect::route('appointments')->with('success', 'A consulta foi cadastrada com sucesso.');
    }

    public function update(): RedirectResponse
    {
        $data = Request::validate([
            'reason'    => ['required', 'integer', 'between:0,2'],
            'animal_id' => ['required', 'integer'],
            'value'     => ['required', 'decimal:2'],
        ]);
    
        if ($data['reason'] == 0) {
            Request::validate([
                'which_vaccines' => ['required', 'max:255'],
            ]);
        }  elseif ($data['reason'] == 1) {
            Request::validate([
                'observations_description'     => ['required', 'max:255'],
                'up_to_date_vaccines'          => ['required', 'max:255'],
                'up_to_date_deworming'         => ['required', 'max:255'],
                'use_antiparasitics'           => ['required', 'max:255'],
                'previous_surgeries'           => ['required', 'max:255'],
                'illnesses_chronic_conditions' => ['required', 'max:255'],
                'medication'                   => ['required', 'max:255'],
                'exams'                        => ['required', 'max:255'],
            ]);
        } elseif ($data['reason'] == 2) {
            Request::validate([
                'up_to_date_vaccines'          => ['required', 'max:255'],
                'up_to_date_deworming'         => ['required', 'max:255'],
                'use_antiparasitics'           => ['required', 'max:255'],
                'previous_surgeries'           => ['required', 'max:255'],
                'illnesses_chronic_conditions' => ['required', 'max:255'],
                'medication'                   => ['required', 'max:255'],
                'main_symptoms'                => ['required', 'max:255'],
                'change_behavior'              => ['required', 'max:255'],
                'temperature'                  => ['required', 'max:255'],
                'heart_respiratory_rate'       => ['required', 'max:255'],
                'mucous_membranes_hydration'   => ['required', 'max:255'],
                'weight'                       => ['required', 'max:255'],
                'pain_scale'                   => ['required', 'max:255'],
                'skin_coat'                    => ['required', 'max:255'],
                'eyes_ears'                    => ['required', 'max:255'],
                'oral_cavity'                  => ['required', 'max:255'],
                'abdomen_locomotion'           => ['required', 'max:255'],
                'reproductive_system'          => ['required', 'max:255'],
                'exams'                        => ['required', 'max:255'],
            ]);
        }

        $appointment = Appointment::whereId(Request::get('id'))->first();
        $appointment->reason                        = Request::get('reason');
        $appointment->value                         = Request::get('value');
        $appointment->which_vaccines                = Request::get('which_vaccines');
        $appointment->up_to_date_vaccines           = Request::get('up_to_date_vaccines');
        $appointment->up_to_date_deworming          = Request::get('up_to_date_deworming');
        $appointment->use_antiparasitics            = Request::get('use_antiparasitics');
        $appointment->previous_surgeries            = Request::get('previous_surgeries');
        $appointment->illnesses_chronic_conditions  = Request::get('illnesses_chronic_conditions');
        $appointment->medication                    = Request::get('medication');
        $appointment->main_symptoms                 = Request::get('main_symptoms');
        $appointment->change_behavior               = Request::get('change_behavior');
        $appointment->temperature                   = Request::get('temperature');
        $appointment->heart_respiratory_rate        = Request::get('heart_respiratory_rate');
        $appointment->mucous_membranes_hydration    = Request::get('mucous_membranes_hydration');
        $appointment->weight                        = Request::get('weight');
        $appointment->pain_scale                    = Request::get('pain_scale');
        $appointment->skin_coat                     = Request::get('skin_coat');
        $appointment->eyes_ears                     = Request::get('eyes_ears');
        $appointment->oral_cavity                   = Request::get('oral_cavity');
        $appointment->abdomen_locomotion            = Request::get('abdomen_locomotion');
        $appointment->reproductive_system           = Request::get('reproductive_system');
        $appointment->exams                         = Request::get('exams');
        $appointment->observations_description      = Request::get('observations_description');
        $appointment->animal_id                     = Request::get('animal_id');
        $appointment->save();

        return Redirect::route('appointments')->with('success', 'A consulta foi atualizada com sucesso.');
    }

    public function destroy(): RedirectResponse
    {
        $appointment = Appointment::whereId(Request::get('id'))->first();
        $appointment->delete();

        return Redirect::back()->with('success', 'A consulta foi deletada com sucesso.');
    }
}
