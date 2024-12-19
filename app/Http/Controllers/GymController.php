<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class GymController extends Controller {
    public function our_gyms() {
        $gyms = Gym::all();

        return view('user.our_gyms', [
            'gyms' => $gyms
        ]);
    }


    public function create_new_gym_page() {
        return view('admin.create_new_gym');
    }

    public function create_new_gym(Request $request) {

        $form_data = $request->validate([
            'name' => ['required', 'max:50', 'unique:gyms'],
            'description' => ['required', 'max:1000'],
            'address' => ['required', 'max:100', 'unique:gyms']
        ]);

        $gym = Gym::create([
            'name' => $form_data['name'],
            'description' => $form_data['description'],
            'address' => $form_data['address']
        ]);

        return redirect()->route('our_gyms')->with('message', 'Jauna sporta zāle veiksmīgi izveidota!');
    }


    public function edit_gym_page(Request $request) {
        $gym = Gym::where('gym_id', $request->gym_id)->first();

        return view('admin.edit_gym', [
            'gym' => $gym
        ]);
    }


    public function edit_gym(Request $request) {

        $form_data = $request->validate([
            'name' => ['required', 'max:50', 'unique:gyms,name,' . $request->gym_id . ',gym_id'],
            'description' => ['required', 'max:1000'],
            'address' => ['required', 'max:100', 'unique:gyms,address,' . $request->gym_id . ',gym_id']
        ]);

        $gym = Gym::where('gym_id', $request->gym_id)->first();

        $gym->update([
            'name' => $form_data['name'],
            'description' => $form_data['description'],
            'address' => $form_data['address']
        ]);

        return redirect()->route('our_gyms')->with('message', 'Sporta zāles informācija veiksmīgi atjaunota!');
    }
}
