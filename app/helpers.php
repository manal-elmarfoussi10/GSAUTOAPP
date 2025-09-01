<?php

if (!function_exists('fix_public_path')) {
    function fix_public_path()
    {
        return base_path('public_html');
    }
}