<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use DateTime;
use App\Http\Controllers\NotificationsController;
use App\Services\FinancialService;

class FinancialController extends Controller
{
    protected FinancialService $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index(): Response
    {
        $financials = Financial::where('user_id', Auth::id())->get();

        $outflow = 0;
        $inflow  = 0;

        foreach ($financials as $financial) {
            $financial->date_formated = (new DateTime($financial->date))->format('d/m/Y');
            $financial->type_formated = $financial->type == 0 ? 'Saída' : 'Entrada';
            $financial->json_data     = $financial->toJson();

            if ($financial->type == 0) {
                $outflow += $financial->value;
            } else {
                $inflow += $financial->value;
            }
        }

        $total = $inflow - $outflow;

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
        $data = Request::validate([
            'name'  => ['required', 'max:255'],
            'type'  => ['required', 'max:255'],
            'date'  => ['required', 'date'],
            'value' => ['required', 'decimal:2'],
        ]);

        $this->financialService->store($data, Auth::id());

        return Redirect::route('financial')->with('success', 'O registro foi cadastrado com sucesso.');
    }

    public function update(): RedirectResponse
    {
        $data = Request::validate([
            'id'    => ['required', 'integer'],
            'name'  => ['required', 'max:255'],
            'type'  => ['required', 'max:255'],
            'date'  => ['required', 'date'],
            'value' => ['required', 'decimal:2'],
        ]);

        $this->financialService->update($data['id'], $data);

        return Redirect::route('financial')->with('success', 'O registro foi editado com sucesso.');
    }

    public function destroy(): RedirectResponse
    {
        $id = Request::validate([
            'id' => ['required', 'integer'],
        ])['id'];

        $this->financialService->destroy($id);

        return Redirect::back()->with('success', 'O registro foi deletado com sucesso.');
    }
}
