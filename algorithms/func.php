<?php

function fn_get_array_of_random_numbers($size = 0)
{
    $result = array();

    srand((double) microtime() * 1000000);

    for($x = 0; $x < $size; $x++) {
        $i = rand(1, time()/10000);
        $result[] = $i;
    }

    return $result;
}
