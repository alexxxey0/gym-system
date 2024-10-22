<?php

// Add dash to the personal ID if it was omitted

function add_dash($personal_id) {
    // Check if the ID matches the pattern without a dash
    if (preg_match('/^\d{6}\d{5}$/', $personal_id)) {
        // Add a dash after the 6th digit
        $personal_id = preg_replace('/^(\d{6})(\d{5})$/', '$1-$2', $personal_id);
    }
    return $personal_id;
}
