<?php

namespace App\Rules;

use Closure;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class valid_schedule implements ValidationRule {
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $invalid_days_array_eng = array();

        foreach ($value as $day => $schedule) {

            $day_start_time = Carbon::parse($schedule['start']);
            $day_end_time = Carbon::parse($schedule['end']);
            $gym_opening_weekday = Carbon::parse('08:00');
            $gym_closing_weekday = Carbon::parse('22:00');
            $gym_opening_weekend = Carbon::parse('09:00');
            $gym_closing_weekend = Carbon::parse('20:00');

            // Validate that the start time is set
            if (!isset($day_start_time) or !$day_start_time) {
                $invalid_days_array_eng[] = $day;
            }

            // Validate that the end time is set
            if (!isset($day_end_time) or !$day_end_time) {
                $invalid_days_array_eng[] = $day;
            }

            // Validate that the end time is not before or at the same time as start time
            if ($day_end_time->lessThanOrEqualTo($day_start_time)) {
                $invalid_days_array_eng[] = $day;
            }

            // Validate that the training duration is at least 30 minutes
            if ($day_start_time->diffInMinutes($day_end_time) < 30) {
                $invalid_days_array_eng[] = $day;
            }

            // Validate that the training duration is no more than 120 minutes
            if ($day_start_time->diffInMinutes($day_end_time) > 120) {
                $invalid_days_array_eng[] = $day;
            }

            // Validate that the training takes place entirely during the gym working hours
            if (in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'])) {
                if ($day_start_time->lessThan($gym_opening_weekday) or $day_end_time->greaterThan($gym_closing_weekday)) {
                    $invalid_days_array_eng[] = $day;
                }
            } else {
                if ($day_start_time->lessThan($gym_opening_weekend) or $day_end_time->greaterThan($gym_closing_weekend)) {
                    $invalid_days_array_eng[] = $day;
                }
            }
        }

        if (count($invalid_days_array_eng) > 0) {
            $days_eng = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $days_lv = ['Pirmdiena', 'Otrdiena', 'Trešdiena', 'Ceturtdiena', 'Piektdiena', 'Sestdiena', 'Svētdiena'];


            $invalid_days_array_lv = array();
            for ($i = 0; $i < count($days_eng); $i++) {
                if (in_array($days_eng[$i], $invalid_days_array_eng)) {
                    $invalid_days_array_lv[] = $days_lv[$i];
                }
            }
            // Creating the string with the invalid days in Latvian
            $invalid_days = implode(', ', $invalid_days_array_lv);

            $message = "Nodarbības grafiks dienām: $invalid_days neatbilst noteikumiem. Katrai dienai pārliecienieties ka:\n- Nodarbībai ir norādīti sākuma un beigu laiki\n- Nodarbības beigu laiks nav agrāks vai vienāds ar sākuma laiku\n- Nodarbības ilgums ir vismaz 30 minūtes\n- Nodarbības ilgums nepārsniedz 120 minūtes\n- Nodarbībai jānotiek sporta zāles darba laikā (darba dienās 08:00-22:00, brīvdienās 09:00-20:00)";

            $fail($message);
        }
    }
}
