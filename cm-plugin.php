<?php
/*
Plugin Name: campaign monitor
Description: adding the new wc customers to a campaing monitor list
Version: 1.0
Author: kasra sabet
*/



add_action('user_register', 'add_user_to_campaign_monitor');

function add_user_to_campaign_monitor($user_id) {
    error_log('User registered. User ID: ' . $user_id);
    // Retrieve user information from WordPress
    $user_data = get_user_data($user_id);

    // Add user to Campaign Monitor list
    add_user_to_list_in_campaign_monitor($user_data);
}

function get_user_data($user_id) {

    $user = get_userdata($user_id);
    error_log('im here: ' . $user->user_email);
    $user_data = [
        'email' => $user->user_email,
        'name' => $user->display_name,
    ];

    return $user_data;
}

function add_user_to_list_in_campaign_monitor($user_data) {
    $api_key = 'KY1cXgX8556t04vEyr40ipHguMfhQ3dCn8umXO7x7pTi2ao9VNvPnJ+eksfwaQF9KBFI7OV+/muu4qRe6DH4NJD08F4VOeaHm39VJrdjk5yY8+2VL4gjOjXKivR5U6Gebffan39QkAXkShDQn2kuMg==';
    $list_id = '9be53c37f36e666004532e1cd7b0859f'; 

    $api_url = "https://api.createsend.com/api/v3.3/subscribers/{$list_id}.json";  
    $request_body = json_encode([
        'EmailAddress' => $user_data['email'],
        'Name' => $user_data['name'],
        'ConsentToTrack' => 'Yes',
    ]);

    $request_headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ' . base64_encode($api_key . ':'), //
    ];

    $response = wp_remote_post($api_url, [
        'headers' => $request_headers,
        'body' => $request_body,
    ]);

    // Handle the API response (check for success, log errors, etc.)
    if (is_wp_error($response)) {
        error_log('Campaign Monitor API request failed: ' . $response->get_error_message());
    } else {
        // Log or handle success
        echo '<pre>' . print_r($response, true) . '</pre>';
       exit;
    }
}
?>