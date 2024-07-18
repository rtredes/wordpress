<?php

// Function to create a new post via REST API
function create_post_via_api($post_title, $post_content)
{
    $url = 'http://yoursite.com/wp-json/wp/v2/posts';

    $post_data = array(
        'title' => $post_title,
        'content' => $post_content,
        'status' => 'publish'
    );

    $response = wp_remote_post($url, array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('username:password') // Replace with your credentials
        ),
        'body' => json_encode($post_data)
    ));

    if (is_wp_error($response)) {
        return false;
    } else {
        return true;
    }
}

// Function to retrieve posts via REST API
function get_posts_via_api()
{
    $url = 'http://yoursite.com/wp-json/wp/v2/posts';

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return null;
    } else {
        $posts = json_decode(wp_remote_retrieve_body($response));
        return $posts;
    }
}

// Function to update an existing post via REST API
function update_post_via_api($post_id, $post_title, $post_content)
{
    $url = 'http://yoursite.com/wp-json/wp/v2/posts/' . $post_id;

    $post_data = array(
        'title' => $post_title,
        'content' => $post_content
    );

    $response = wp_remote_post($url, array(
        'method' => 'PUT', // Use 'PUT' or 'PATCH' for update
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('username:password') // Replace with your credentials
        ),
        'body' => json_encode($post_data)
    ));

    if (is_wp_error($response)) {
        return false;
    } else {
        return true;
    }
}

// Function to delete a post via REST API
function delete_post_via_api($post_id)
{
    $url = 'http://yoursite.com/wp-json/wp/v2/posts/' . $post_id;

    $response = wp_remote_request($url, array(
        'method' => 'DELETE',
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('username:password') // Replace with your credentials
        )
    ));

    if (is_wp_error($response)) {
        return false;
    } else {
        return true;
    }
}
