<?php
/**
 * Laravel Helper Functions Stubs untuk IDE Autocomplete
 * File ini tidak dijalankan, hanya untuk memberikan hints ke IDE
 */

if (!function_exists('view')) {
    /**
     * @param string $view
     * @param array $data
     * @return \Illuminate\View\View
     */
    function view($view, $data = []) {}
}

if (!function_exists('redirect')) {
    /**
     * @return \Illuminate\Routing\Redirector
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null) {}
}

if (!function_exists('back')) {
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    function back($status = 302, $headers = [], $fallback = false) {}
}

if (!function_exists('session')) {
    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    function session($key = null, $default = null) {}
}

if (!function_exists('now')) {
    /**
     * @return \Illuminate\Support\Carbon
     */
    function now($tz = null) {}
}

if (!function_exists('base_path')) {
    /**
     * @param string $path
     * @return string
     */
    function base_path($path = '') {}
}
