<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: https://music.hampoelz.net");
    //header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET"); 
} 

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if (!$url) {
    die("No url provided");
}

$youtube = new \YouTube\YouTubeDownloader();
$links = $youtube->getDownloadLinks($url);

if (empty($links)) {
  die("Not Found");
}

header('Content-Type: application/json');
echo json_encode($links, JSON_PRETTY_PRINT);
