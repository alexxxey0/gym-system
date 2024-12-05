<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller {
    public function gym_statistics_page() {

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
        function get_income_data($period) {
            if ($period === 'week') $days = 7;
            elseif ($period === 'month') $days = 30;
            elseif ($period === 'year') $days = 365;

            if ($period !== 'all')
                $payments_info = Payment::where('created_at', '>=', Carbon::now()->subDays($days))->where('payment_status', 'COMPLETED')->get();
            else {
                $payments_info = Payment::where('payment_status', 'COMPLETED')->get();
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


        return view('admin.gym_statistics', [
            'memberships_distribution' => json_encode($memberships_distribution),
            'payments_data' => json_encode($payments_data)
        ]);
    }
}
