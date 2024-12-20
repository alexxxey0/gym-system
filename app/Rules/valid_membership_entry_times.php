<?php

namespace App\Rules;

use Closure;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class valid_membership_entry_times implements ValidationRule {
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $entry_times = array();
        foreach ($value as $entry_type => $entry_time) {
            $entry_times[$entry_type] = Carbon::parse($entry_time);
        }

        foreach ($entry_times as $entry_type => $entry_time) {
            if (!isset($entry_time) or !$entry_time) {
                $fail('Lūdzu, aizpildiet visus laukus!');
            }

            if ($entry_type === 'entry_from_workdays' or $entry_type === 'entry_until_workdays') {
                if ($entry_time->lessThan('08:00') or $entry_time->greaterThan('22:00')) {
                    $fail('Ieejas laiks iziet ārpus sporta zāļu darba laika (darba dienās — 08:00-22:00)!');
                }
            } else {
                if ($entry_time->lessThan('09:00') or $entry_time->greaterThan('20:00')) {
                    $fail('Ieejas laiks iziet ārpus sporta zāļu darba laika (brīvdienās — 09:00-20:00)!');
                }
            }
        }

        if ($entry_times['entry_from_workdays']->greaterThanOrEqualTo($entry_times['entry_until_workdays']) or $entry_times['entry_from_weekends']->greaterThanOrEqualTo($entry_times['entry_until_weekends'])) {
            $fail('Ieejas sākuma laiks nevar būt vienāds vai vēlāks par ieejas beigu laiku!');
        }
    }
}
