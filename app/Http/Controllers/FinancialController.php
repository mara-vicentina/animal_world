<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use DateTime;
use App\Http\Controllers\NotificationsController;

class FinancialController extends Controller
{
    public function index(): Response
    {
        $financials = Financial::where('user_id', Auth::id())->get();

        $outflow   = 0;
        $inflow    = 0;
        $total     = 0;
        
        foreach ($financials as $financial) {
            $financial->date_formated = (new DateTime($financial->date))->format('d/m/Y');
            $financial->type_formated = $financial->type == 0 ? 'Saída' : 'Entrada';
            $financial->json_data     = $financial->toJson();
            
            if($financial->type == 0){
                $outflow += $financial->value;
            } else {
                $inflow += $financial->value;
            }
        }
        $total += $inflow - $outflow;

        $notificacoes = (new NotificationsController())->index()->getData();

        return Inertia::render('Financial/Index', [
            'financial' => $financials,
            'outflow'   => $outflow,
            'inflow'    => $inflow,
            'total'     => $total,
            'notificacoes' => $notificacoes,
        ]);
            
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'name'  => ['required', 'max:255'],
            'type'  => ['required', 'max:255'],
            'date'  => ['required', 'date'],
            'value' => ['required', 'decimal:2'],
        ]);

        $financial = new Financial();
        $financial->name     = Request::get('name');
        $financial->type     = Request::get('type');
        $financial->date     = Request::get('date');
        $financial->value    = Request::get('value');
        $financial->user_id  = Auth::id();
        $financial->save();

        return Redirect::route('financial')->with('success', 'O registro foi cadastrado com sucesso.');
    }

    public function update(): RedirectResponse
    {
        Request::validate([
            'name'  => ['required', 'max:255'],
            'type'  => ['required', 'max:255'],
            'date'  => ['required', 'date'],
            'value' => ['required', 'decimal:2'],
        ]);

        $financial = Financial::whereId(Request::get('id'))->first();
        $financial->name     = Request::get('name');
        $financial->type     = Request::get('type');
        $financial->date     = Request::get('date');
        $financial->value    = Request::get('value');
        $financial->save();

        return Redirect::route('financial')->with('success', 'O registro foi editado com sucesso.');
    }

    public function destroy(): RedirectResponse
    {
        $financial = Financial::whereId(Request::get('id'))->first();
        $financial->delete();

        return Redirect::back()->with('success', 'O registro foi deletado com sucesso.');
    }
}
