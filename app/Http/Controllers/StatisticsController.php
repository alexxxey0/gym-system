<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gym;
use App\Models\Coach;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\GroupTraining;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller {
    public function gym_statistics_page() {
        $gyms_statistics = array();

        // Getting data across all gyms

        // Preparing data about memberships distribution between clients
        $clients = Client::all();
        $memberships = Membership::all();
        $memberships_distribution = array();

        foreach ($memberships as $membership) {
            $memberships_distribution[$membership->membership_id] = 0;
        }
        $memberships_distribution['No membership'] = 0;

        foreach ($clients as $client) {
            if (isset($client->membership_id)) {
                $memberships_distribution[$client->membership_id]++;
            } else {
                $memberships_distribution['No membership']++;
            }
        }

        foreach ($memberships_distribution as $membership_id => $count) {
            if ($membership_id !== 'No membership') {
                $membership_info = Membership::where('membership_id', $membership_id)->first();
                $memberships_distribution[$membership_info->membership_name] = $memberships_distribution[$membership_id];
                unset($memberships_distribution[$membership_id]);
            }
        }

        // Preparing data about incomes
        $income_data = array();

        // Last week payments
        function get_income_data($period, $gym_id = null) {
            if ($period === 'week') $days = 7;
            elseif ($period === 'month') $days = 30;
            elseif ($period === 'year') $days = 365;

            if (!isset($gym_id)) {
                if ($period !== 'all')
                    $payments_info = Payment::where('created_at', '>=', Carbon::now()->subDays($days))->where('payment_status', 'COMPLETED')->get();
                else {
                    $payments_info = Payment::where('payment_status', 'COMPLETED')->get();
                }
            } else {
                if ($period !== 'all')
                    $payments_info = Payment::where('created_at', '>=', Carbon::now()->subDays($days))->where('payment_status', 'COMPLETED')->whereHas('client', function ($query) use ($gym_id) {
                        $query->where('gym_id', $gym_id);
                    })->get();
                else {
                    $payments_info = Payment::where('payment_status', 'COMPLETED')->whereHas('client', function ($query) use ($gym_id) {
                        $query->where('gym_id', $gym_id);
                    })->get();
                }
            }

            $payments = array();
            foreach ($payments_info as $payment_info) {
                if (!array_key_exists(Carbon::parse($payment_info->created_at)->format('Y-m-d'), $payments)) {
                    $payments[Carbon::parse($payment_info->created_at)->format('Y-m-d')] = floatval($payment_info->amount);
                } else {
                    $payments[Carbon::parse($payment_info->created_at)->format('Y-m-d')] += floatval($payment_info->amount);
                }
            }
            ksort($payments);
            $total = array_sum($payments);
            $data = ['payments' => $payments, 'total' => $total];
            return $data;
        }

        $periods = ['week', 'month', 'year', 'all'];
        foreach ($periods as $period) {
            $payments_data[$period] = get_income_data($period);
            // For long periods of time, group incomes by months instead of displaying individual days
            if ($period === 'year' or $period === 'all') {

                // Initialize an empty array to store grouped incomes
                $grouped_incomes = array();

                // Loop through each income
                foreach ($payments_data[$period]['payments'] as $date => $income) {
                    // Extract the month and year
                    $month = Carbon::parse($date)->format('Y-m');

                    // Add the income to the corresponding month
                    if (!isset($grouped_incomes[$month])) {
                        $grouped_incomes[$month] = 0;
                    }
                    $grouped_incomes[$month] += floatval($income);
                }
                $payments_data[$period]['payments'] = $grouped_incomes;
            }
        }

        $clients_count = Client::count();
        $coaches_count = Coach::count();
        $active_group_trainings_count = GroupTraining::where('active', true)->count();
        $avg_group_training_attendance = round((Attendance::where('attended', true)->count() / Attendance::count()) * 100, 2);

        $total_statistics = array([
            'memberships_distribution' => $memberships_distribution,
            'payments_data' => $payments_data,
            'clients_count' => $clients_count,
            'coaches_count' => $coaches_count,
            'active_group_trainings_count' => $active_group_trainings_count,
            'avg_group_training_attendance' => $avg_group_training_attendance
        ]);

        foreach ($total_statistics as $statistic_name => $statistic) {
            $gyms_statistics['total'][$statistic_name] = $statistic;
        }

        // Getting statistic about each individual gym
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            $gym_id = $gym->gym_id;

            // Preparing data about memberships distribution
            $memberships_distribution = array();
            foreach ($memberships as $membership) {
                $memberships_distribution[$membership->membership_id] = 0;
            }
            $memberships_distribution['No membership'] = 0;
            
            $clients = Client::where('gym_id', $gym_id)->get();
            foreach ($clients as $client) {
                if (isset($client->membership_id)) {
                    $memberships_distribution[$client->membership_id]++;
                } else {
                    $memberships_distribution['No membership']++;
                }
            }
    
            foreach ($memberships_distribution as $membership_id => $count) {
                if ($membership_id !== 'No membership') {
                    $membership_info = Membership::where('membership_id', $membership_id)->first();
                    $memberships_distribution[$membership_info->membership_name] = $memberships_distribution[$membership_id];
                    unset($memberships_distribution[$membership_id]);
                }
            }

            // Preparing data about incomes
            $periods = ['week', 'month', 'year', 'all'];
            foreach ($periods as $period) {
                $payments_data[$period] = get_income_data($period, $gym_id);
                // For long periods of time, group incomes by months instead of displaying individual days
                if ($period === 'year' or $period === 'all') {

                    // Initialize an empty array to store grouped incomes
                    $grouped_incomes = array();

                    // Loop through each income
                    foreach ($payments_data[$period]['payments'] as $date => $income) {
                        // Extract the month and year
                        $month = Carbon::parse($date)->format('Y-m');

                        // Add the income to the corresponding month
                        if (!isset($grouped_incomes[$month])) {
                            $grouped_incomes[$month] = 0;
                        }
                        $grouped_incomes[$month] += floatval($income);
                    }
                    $payments_data[$period]['payments'] = $grouped_incomes;
                }
            }

            $clients_count = Client::where('gym_id', $gym_id)->count();
            $coaches_count = Coach::count();
            $active_group_trainings_count = GroupTraining::where('gym_id', $gym_id)->where('active', true)->count();

            $gym_attendance = Attendance::whereHas('client', function ($query) use ($gym_id) {
                $query->where('gym_id', $gym_id);
            })->get();
            $avg_group_training_attendance = round(($gym_attendance->where('attended', true)->count() / $gym_attendance->count()) * 100, 2);

            $gym_statistics = array([
                'memberships_distribution' => $memberships_distribution,
                'payments_data' => $payments_data,
                'clients_count' => $clients_count,
                'coaches_count' => $coaches_count,
                'active_group_trainings_count' => $active_group_trainings_count,
                'avg_group_training_attendance' => $avg_group_training_attendance
            ]);

            foreach ($gym_statistics as $statistic_name => $statistic) {
                $gyms_statistics[$gym->gym_id][$statistic_name] = $statistic;
            }
        }

        //dd($gyms_statistics);
        return view('admin.gym_statistics', [
            'gyms_statistics' => $gyms_statistics,
            'gyms_statistics_json' => json_encode($gyms_statistics),
            'gyms' => $gyms
        ]);
    }
}
