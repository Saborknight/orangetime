<?php
defined('ABSPATH') or die();
/**
 * Add actions at initialization to start the session
 * End the session on logout and login
 *
 * @since 1.0.0
 *
 */

function oi_session_start()
{
    if( !session_id() )
    {
        session_start();
    }
}

function oi_session_end() {
    session_destroy();
}

add_action('init', 'oi_session_start', 1);
add_action('wp_logout', 'oi_session_end');
add_action('wp_login', 'oi_session_end');

/**
 * Get a value from the session array
 *
 * @param type $key the key in the array
 * @param type $default the value to use if the key is not present. empty string if not present
 * @return type the value found or the default if not found
 *
 * @since 1.0.0
 *
 */

function oi_session_get($key, $default = '')
{
    if( isset($_SESSION[$key]) )
        return $_SESSION[$key];

    return $default;
}

/**
 * Set a value in the session array
 *
 * @param type $key the key in the array
 * @param type $value the value to set
 *
 * @since 1.0.0
 *
 */
function oi_session_set($key, $value)
{
    $_SESSION[$key] = $value;
}

/**
 * Lisame oma HTTP vastuse päised
 *
 *
 * @since 1.0.0
 *
 */

function oi_http_response($headers)
{

    if( is_admin() )
        return $headers;

    unset($headers['X-Pingback']);

    return $headers;
}
add_filter('wp_headers', 'oi_http_response');
