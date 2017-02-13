<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'func.php');

/**
 * Даны 2012 гирек разной массы. Они разбиты на две группы (по 1006 в каждой),
 * внутри которых упорядочены по массе. Предлжите способ за 11 взвешиваний
 * найти 1006-ую гирьку по массе среди всех.
 */

define('SET_SIZE', 2012);
$weighing_attempts = 11;

$array = fn_get_array_of_random_numbers(SET_SIZE);
list($a, $b) = array_chunk($array, SET_SIZE/2);
sort($a);
sort($b);

function fn_get_mid_index_of_array($array)
{
    $index = 0;

    if (is_array($array)) {
        $index = floor(count($array)/2);
    }

    return $index;
}

$attemtps_left  = 100;

while ($weighing_attempts > 0 && $attemtps_left > 0) {
    $a_index = fn_get_mid_index_of_array($a);
    $b_index = fn_get_mid_index_of_array($b);

    if ($a[$a_index] > $b[$b_index]) {
    } else {
    }

    $attemtps_left--;
}
