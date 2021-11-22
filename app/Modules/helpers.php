<?php 

function test(){
    return 'helpers test!';
}


//This function takes in latitude and longitude of two location and returns the distance between them as the crow flies (in km)
function calcuateDistance($lat1, $lon1, $lat2, $lon2) {
    $PI =  3.1415926535898;     //PI value
    // The math module contains a function
    // named toRadians which converts from
    // degrees to radians.
    $lon1 = $lon1 * $PI / 180;
    $lon2 = $lon2 * $PI / 180;
    $lat1 = $lat1 * $PI / 180;
    $lat2 = $lat2 * $PI / 180;

    // Haversine formula
    $dlon = $lon2 - $lon1;
    $dlat = $lat2 - $lat1;
    $a = pow(sin($dlat / 2), 2) +
        cos($lat1) * cos($lat2) *
        pow(sin($dlon / 2), 2);

    $c = 2 * asin(sqrt($a));

    // Radius of earth in kilometers. Use 3956
    // for miles
    $r = 6371;

    // calculate the result
    return ($c * $r);
}