<?php
$_SERVER['REQUEST_METHOD'] = 'POST';

$_POST = [
    'guest_name' => 'John Doe 3NF',
    'email' => 'john3nf@example.com',
    'address' => '123 Fake St, Cloud City',
    'contact_no' => '555-0199',
    'nationality' => 'Indian',
    'other_nationality_text' => '',
    'room_no' => '404',
    'check_in' => '2026-03-09',
    'check_out' => '2026-03-10',
    'first_stay' => 'Yes',
    'purpose_of_stay' => 'Leisure',
    'other_purpose_text' => '',
    'overall_rating' => 9,
    'suggestions_future' => 'More pillows',
    'other_comments' => 'Great stay!',
    'frontdesk' => 10,
    'reservations' => 9,
    'telephone_operator' => 8,
    'valet' => 0,
    'housekeeping' => 10,
    'accommodation' => 9,
    'safety' => 10,
    'security' => 10,
    'overall_service' => 9,
    'frontdesk_comments' => 'Very welcoming.',
    'food_quality' => 8,
    'serving_time' => 7,
    'wait_staff' => 9,
    'grooming' => 10,
    'behavior' => 10,
    'fnb_service' => 9,
    'bar' => 0,
    'bartender' => 0,
    'fnb_comments' => 'Food was good.',
    'helpful_staff_names' => 'Alice, Bob, Charlie'
];

require 'submit_feedback.php';
echo "Ran submission script. Check DB.\n";
