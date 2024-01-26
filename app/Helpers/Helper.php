<?php

if (!function_exists('toHours')) {
    function toHours($time) {
        $day = floor($time / 86400);
        $hours = floor(($time -($day*86400)) / 3600);
        $minutes = floor(($time / 60) % 60);
        $seconds = $time % 60;
        return "$hours:$minutes:$seconds";
    }
}