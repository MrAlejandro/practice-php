<?php

// define custom functions

if (!function_exists('startsWith')) {
    function startWith($haystack, $needle)
    {
        return $haystack === "" || strpos($haystack, $needle, - strlen($haystack)) !== false;
    }
}

if (!function_exists('stringContaints')) {
    function stringContaints($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('base_path')) {
    function base_path()
    {
        $temp_path = __DIR__;
        $base_path = str_replace('bootstrap', '', $temp_path);
        return $base_path;
    }
}

