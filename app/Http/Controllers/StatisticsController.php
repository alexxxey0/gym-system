<?php

namespace App\Http\Controllers;

use App\Models\Client;
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

        return view('admin.gym_statistics', [
            'memberships_distribution' => json_encode($memberships_distribution)
        ]);
    }
}
