<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: https://music.hampoelz.net");
    //header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");
}

$url = isset($_GET['url']) ? $_GET['url'] : null;

if (!$url) {
    die("No url provided");
}

$youtube = "http://www.youtube.com/oembed?url=" . $url . "&format=json";
$curl = curl_init($youtube);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$return = curl_exec($curl);
curl_close($curl);

header('Content-Type: application/json');
echo $return;
