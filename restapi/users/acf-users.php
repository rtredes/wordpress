<?php

// Callback function to retrieve users with ACF fields
function get_acf_users($request)
{
    $args = array(
        'fields' => 'all',
        // Add any additional parameters here as needed
    );

    // Pagination parameters
    $page = isset($request['page']) ? absint($request['page']) : 1;
    $per_page = isset($request['per_page']) ? absint($request['per_page']) : 10;

    // Calculate offset
    $offset = ($page - 1) * $per_page;
    $args['number'] = $per_page;
    $args['offset'] = $offset;

    // Fetch users based on arguments
    $users = get_users($args);

    $acf_users = array();

    foreach ($users as $user) {
        $user_data = array(
            'ID' => $user->ID,
            'user_login' => $user->user_login,
            'user_email' => $user->user_email,
            // Add more user fields as needed
        );

        // Get ACF fields for the user
        $acf_fields = get_fields('user_' . $user->ID);

        if ($acf_fields) {
            foreach ($acf_fields as $key => $value) {
                // Append ACF fields to user data
                $user_data[$key] = $value;
            }
        }

        $acf_users[] = $user_data;
    }

    // Prepare response data for pagination
    $total_users = count_users();
    $total_users_count = $total_users['total_users'];
    $next_page = $page + 1;
    $prev_page = $page - 1;
    $total_pages = ceil($total_users_count / $per_page);

    $response = array(
        'per_page' => count($acf_users),
        'count' => $total_users_count,
        'next' => ($next_page <= $total_pages) ? rest_url('custom/v1/acf_users/?page=' . $next_page . '&per_page=' . $per_page) : null,
        'prev' => ($prev_page > 0) ? rest_url('custom/v1/acf_users/?page=' . $prev_page . '&per_page=' . $per_page) : null,
        'users' => $acf_users,
    );

    return rest_ensure_response($response);
}

// Add custom endpoint for fetching users with ACF fields
function custom_acf_users_endpoint()
{
    register_rest_route('custom/v1', '/acf_users/', array(
        'methods' => 'GET',
        'callback' => 'get_acf_users',
        'permission_callback' => '__return_true', // Adjust as per your authentication/authorization needs
    ));
}
add_action('rest_api_init', 'custom_acf_users_endpoint');
