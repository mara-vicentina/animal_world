<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Client;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Models\Notification;

class NotificationsController extends Controller
{
     public function index(): JsonResponse
    {
        $notificacoes = [];

        $animais = Animal::join('clients', 'clients.id', '=', 'animals.client_id')
            ->join('users', 'users.id', '=', 'clients.user_id')
            ->where('users.id', Auth::id())
            ->select('animals.id', 'animals.name', 'animals.client_id') // 👈 necessário!
            ->get();

        foreach ($animais as $animal) {
            $ultimaConsulta = Appointment::where('animal_id', $animal->id)
                ->orderByDesc('created_at')
                ->first();

            if ($ultimaConsulta) {
                $dataConsulta = Carbon::parse($ultimaConsulta->created_at);
                $meses = intval($dataConsulta->diffInMonths(Carbon::now()));

                if ($meses >= 6) {
                    $client = Client::find($animal->client_id);
                    if (!$client) continue;

                    $mensagem = "O animal {$animal->name} está sem consulta há $meses meses. Entre em contato com o Tutor {$client->name}.";

                    // Evitar notificações duplicadas
                    $existe = Notification::where('user_id', Auth::id())
                        ->where('animal_name', $animal->name)
                        ->where('last_appointment_date', $dataConsulta->toDateString())
                        ->exists();

                    if (!$existe) {
                        Notification::create([
                            'user_id' => Auth::id(),
                            'animal_name' => $animal->name,
                            'client_name' => $client->name,
                            'message' => $mensagem,
                            'last_appointment_date' => $dataConsulta->toDateString(),
                            'read' => false,
                        ]);
                    }
                }
            }
        }

        $notificacoes = Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($notificacoes);
    }
}
