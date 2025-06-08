<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use App\Models\Schedule;
use App\Models\Animal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Datetime;
use App\Http\Controllers\NotificationsController;

class ScheduleController extends Controller
{
    public function index(): Response
    {
        $schedules = Schedule::join('animals', 'animals.id', '=', 'schedules.animal_id')
            ->join('clients', 'clients.id', '=', 'animals.client_id')
            ->join('users', 'users.id', '=', 'clients.user_id')
            ->where('users.id', Auth::user()->id)
            ->select('schedules.*')
            ->get();

        foreach ($schedules as $schedule) {
            $animal               = Animal::whereId($schedule->animal_id)->first();
            $schedule->client_id  = Client::whereId($animal->client_id)->first()->id;
            $schedule->start_date = (new Datetime($schedule->start_event))->format('Y-m-d');
            $schedule->start_time = (new Datetime($schedule->start_event))->format('H:i');
            $schedule->end_date = (new Datetime($schedule->end_event))->format('Y-m-d');
            $schedule->end_time = (new Datetime($schedule->end_event))->format('H:i');
        }

        $notificacoes = (new NotificationsController())->index()->getData();

        return Inertia::render('Schedule/Index', [
            'clients'   => Client::get(['id', 'name']),
            'animals'   => Animal::get(['id', 'name', 'client_id']),
            'schedules' => $schedules,
            'notificacoes' => $notificacoes,
        ]);
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'title'  => ['required', 'max:255'],
            'animal_id' => ['required', 'integer'],
            'start_date'  => ['required', 'date'],
            'end_date'  => ['required', 'date'],
            'start_time'  => ['required'],
            'end_time'  => ['required'],
            'color' => ['required', 'max:25'],
        ]);

        $schedule = new Schedule();
        $schedule->title        = Request::get('title');
        $schedule->start_event  = Request::get('start_date') . ' ' . Request::get('start_time') . ':00';
        $schedule->end_event    = Request::get('end_date') . ' ' . Request::get('end_time') . ':00';
        $schedule->color        = Request::get('color');
        $schedule->animal_id    = Request::get('animal_id');
        $schedule->save();

        return Redirect::route('schedule')->with('success', 'A consulta foi agendada com sucesso.');
    }

    public function update(): RedirectResponse
    {
        Request::validate([
            'only_title'  => ['required', 'max:255'],
            'start_date'  => ['required', 'date'],
            'end_date'  => ['required', 'date'],
            'start_time'  => ['required'],
            'end_time'  => ['required'],
            'color' => ['required', 'max:25'],
        ]);

        $schedule = Schedule::whereId(Request::get('id'))->first();
        $schedule->title        = Request::get('only_title');
        $schedule->start_event  = Request::get('start_date') . ' ' . Request::get('start_time') . ':00';
        $schedule->end_event    = Request::get('end_date') . ' ' . Request::get('end_time') . ':00';
        $schedule->color        = Request::get('color');
        $schedule->save();

        return Redirect::route('schedule')->with('success', 'A consulta foi agendada com sucesso.');
    }

    public function destroy(): RedirectResponse
    {
        $schedule = Schedule::whereId(Request::get('id'))->first();
        $schedule->delete();

        return Redirect::back()->with('success', 'O agendamento foi deletado com sucesso.');
    }

}
