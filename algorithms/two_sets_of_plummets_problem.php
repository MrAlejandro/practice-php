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
$answer = 0;

$nth_element = (SET_SIZE/2) - 1;

$array = fn_get_array_of_random_numbers(SET_SIZE);
list($a, $b) = array_chunk($array, SET_SIZE/2);
sort($a);
sort($b);
sort($array);

/* echo '<pre>'; */
/* print_r($array); */
/* print_r($a); */
/* print_r($b); */
/* echo '</pre><br/>' . PHP_EOL; */

echo "{$nth_element}th element: {$array[$nth_element]} <br/>" . PHP_EOL;

function fn_get_mid_index_of_array($array)
{
    $index = 0;

    if (is_array($array)) {
        $index = floor(count($array)/2) - 1;
    }

    return $index >= 0 ? $index : 0;
}

while ($weighing_attempts > 0) {
    if (count($a) == 1 && count($b) == 1) {
        $answer = min($a[0], $b[0]);
        break;
    }

    $a_index = fn_get_mid_index_of_array($a);
    $b_index = fn_get_mid_index_of_array($b);

    if ($a[$a_index] < $b[$b_index]) {
        array_splice($a, 0, $a_index + 1);
        array_splice($b, $b_index + 1);
    } else {
        array_splice($b, 0, $b_index + 1);
        array_splice($a, $a_index + 1);
    }

    /* echo '<pre>'; */
    /* print_r($a_index); */
    /* print_r($b_index); */
    /* print_r($a); */
    /* print_r($b); */
    /* echo '</pre>'; */
    /* echo '-------<br/>' . PHP_EOL; */

    $weighing_attempts--;
}

if ($answer) {
    echo "The ansver is: {$answer} <br/>" . PHP_EOL;
} else {
    echo "Algorithm error ((( <br/>" . PHP_EOL;
}
