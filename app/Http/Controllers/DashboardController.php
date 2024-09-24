<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Client;
use App\Models\Animal;
use App\Models\Appointment;
use App\Models\Financial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $initialDate = (new DateTime('first day of this month'))->format('Y-m-d');
        $finalDate = (new DateTime('last day of this month'))->format('Y-m-d');

        if (!empty($request->initialDate) && !empty($request->finalDate)) {
            $initialDate = (new DateTime($request->initialDate))->format('Y-m-d');
            $finalDate = (new DateTime($request->finalDate))->format('Y-m-d');
        }

        $totalClients = Client::where('user_id', Auth::id())
            ->whereBetween('created_at', [$initialDate . ' 00:00:00', $finalDate . ' 23:59:59']);

        $animals = Animal::join('clients', 'clients.id', '=', 'animals.client_id')
            ->join('users', 'users.id', '=', 'clients.user_id')
            ->where('users.id', Auth::id())
            ->whereBetween('animals.created_at', [$initialDate . ' 00:00:00', $finalDate . ' 23:59:59']);

        $appointments = Appointment::join('animals', 'animals.id', '=', 'appointments.animal_id')
            ->join('clients', 'clients.id', '=', 'animals.client_id')
            ->join('users', 'users.id', '=', 'clients.user_id')
            ->where('users.id', Auth::id())
            ->whereBetween('appointments.created_at', [$initialDate . ' 00:00:00', $finalDate . ' 23:59:59']);

        $financials = Financial::where('user_id', Auth::id())
            ->whereBetween('date', [$initialDate . ' 00:00:00', $finalDate . ' 23:59:59']);

        return Inertia::render('Dashboard/Index', [
            'totalClients'                      => $totalClients->count(),
            'totalMalesAnimals'                 => (clone $animals)->where('sex', 0)->count(),
            'totalFemalesAnimals'               => (clone $animals)->where('sex', 1)->count(),
            'totalAppointmentsVaccines'         => (clone $appointments)->where('reason', 0)->count(),
            'totalAppointmentsRoutine'          => (clone $appointments)->where('reason', 1)->count(),
            'totalAppointmentsClinicalAnalysis' => (clone $appointments)->where('reason', 2)->count(),
            'totalFinancialInflow'              => (clone $financials)->where('type', 1)->sum('value'),
            'totalFinancialOutflow'             => (clone $financials)->where('type', 0)->sum('value'),
            'animalsReportChart'                => $this->getTotalAnimalChart(),
            'appointmentsReportChart'           => $this->getTotalAppointmentsChart(),
            'financialReportChart'              => $this->getTotalFinancialChart(),
            'initialDate'                       => $initialDate,
            'finalDate'                         => $finalDate,
        ]);
    }

    private function getTotalAnimalChart()
    {
        $result = [];
        $report = [
            'labels' => [],
            'total' => [],
            'femaleTotal' => [],
            'maleTotal' => [],
        ];

        $currentDate = new DateTime();

        for ($i=0; $i<12; $i++) {
            $initialDate = (clone $currentDate)->modify('first day of this month');
            $finalDate = (clone $currentDate)->modify('last day of this month');

            $result[$currentDate->format('m/Y')] = Animal::join('clients', 'clients.id', '=', 'animals.client_id')
                ->join('users', 'users.id', '=', 'clients.user_id')
                ->select(
                    DB::raw('IFNULL(COUNT(animals.id), 0) as total'),
                    DB::raw('IFNULL(SUM(CASE WHEN animals.sex = 0 THEN 1 ELSE 0 END), 0) as total_male'),
                    DB::raw('IFNULL(SUM(CASE WHEN animals.sex = 1 THEN 1 ELSE 0 END), 0) as total_female'),
                )
                ->where('users.id', Auth::id())
                ->whereBetween('animals.created_at', [$initialDate->format('Y-m-d') . ' 00:00:00', $finalDate->format('Y-m-d') . ' 23:59:59'])
                ->get()
                ->toArray();

            $currentDate->modify('-1 month');
        }

        $result = array_reverse($result);
        $report['labels'] = $this->getMonthLabels($result);

        foreach ($result as $item) {
            $report['total'][] = $item[0]['total'];
            $report['femaleTotal'][] = intval($item[0]['total_female']);
            $report['maleTotal'][] = intval($item[0]['total_male']);
        }

        return $report;
    }

    private function getTotalAppointmentsChart()
    {
        $result = [];
        $report = [
            'labels' => [],
            'totalVaccines' => [],
            'totalRoutine' => [],
            'totalClinicalAnalysis' => [],
        ];

        $currentDate = new DateTime();

        for ($i=0; $i<12; $i++) {
            $initialDate = (clone $currentDate)->modify('first day of this month');
            $finalDate = (clone $currentDate)->modify('last day of this month');

            $result[$currentDate->format('m/Y')] = Appointment::join('animals', 'animals.id', '=', 'appointments.animal_id')
                ->join('clients', 'clients.id', '=', 'animals.client_id')
                ->join('users', 'users.id', '=', 'clients.user_id')
                ->select(
                    DB::raw('IFNULL(SUM(CASE WHEN appointments.reason = 0 THEN 1 ELSE 0 END), 0) as totalVaccines'),
                    DB::raw('IFNULL(SUM(CASE WHEN appointments.reason = 1 THEN 1 ELSE 0 END), 0) as totalRoutine'),
                    DB::raw('IFNULL(SUM(CASE WHEN appointments.reason = 2 THEN 1 ELSE 0 END), 0) as totalClinicalAnalysis'),
                )
                ->where('users.id', Auth::id())
                ->whereBetween('appointments.created_at', [$initialDate->format('Y-m-d') . ' 00:00:00', $finalDate->format('Y-m-d') . ' 23:59:59'])
                ->get()
                ->toArray();

            $currentDate->modify('-1 month');
        }

        $result = array_reverse($result);
        $report['labels'] = $this->getMonthLabels($result);

        foreach ($result as $item) {
            $report['totalVaccines'][] = $item[0]['totalVaccines'];
            $report['totalRoutine'][] = intval($item[0]['totalRoutine']);
            $report['totalClinicalAnalysis'][] = intval($item[0]['totalClinicalAnalysis']);
        }

        return $report;
    }

    private function getTotalFinancialChart()
    {
        $result = [];
        $report = [
            'labels' => [],
            'total' => [],
            'inflow' => [],
            'outflow' => [],
        ];

        $currentDate = new DateTime();

        for ($i=0; $i<12; $i++) {
            $initialDate = (clone $currentDate)->modify('first day of this month');
            $finalDate = (clone $currentDate)->modify('last day of this month');

            $result[$currentDate->format('m/Y')] = Financial::select(
                    DB::raw('IFNULL(SUM(CASE WHEN financial.type = 1 THEN financial.value ELSE 0 END), 0) as inflow'),
                    DB::raw('IFNULL(SUM(CASE WHEN financial.type = 0 THEN financial.value ELSE 0 END), 0) as outflow'),
                )
                ->where('user_id', Auth::id())
                ->whereBetween('financial.date', [$initialDate->format('Y-m-d') . ' 00:00:00', $finalDate->format('Y-m-d') . ' 23:59:59'])
                ->get()
                ->toArray();

            $currentDate->modify('-1 month');
        }

        $result = array_reverse($result);
        $report['labels'] = $this->getMonthLabels($result);

        foreach ($result as $item) {
            $report['total'][] = $item[0]['inflow'] - $item[0]['outflow'];
            $report['inflow'][] = $item[0]['inflow'];
            $report['outflow'][] = $item[0]['outflow'];
        }

        return $report;
    }

    private function getMonthLabels($itens) {
        $labels = [];
        $months = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        ];

        foreach ($itens as $key => $item) {
            $separated = explode('/', $key);
            $labels[] = ($months[$separated[0]] . '/' . $separated[1]);
        }

        return $labels;
    }
}
