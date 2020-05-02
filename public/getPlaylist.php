<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
  //header("Access-Control-Allow-Origin: https://music.hampoelz.net");
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET"); 
} 

$url = isset($_GET['url']) ? $_GET['url'] : null;

if (!$url) {
  die("No url provided");
}

$content = file_get_contents($url);
$count = preg_match_all('|<tr class="pl-video([^>]*)>(.*)</tr>|msiU',$content,$matches);
$videos = [];

for($i=0; $i < $count; $i++) {
  if (!preg_match('|<a([^>]*)>([^<]*)</a>|msi',$matches[0][$i],$link)) continue;
  if (!preg_match('|href="(/watch[^&]*)&amp|i',$link[1],$href)) continue;
  if (!preg_match('|<td class="pl-video-time"([^>]*)>(.*)</td>|msiU',$matches[0][$i],$time)) continue;

  $href = 'https://www.youtube.com'.trim($href[1]);

  array_push($videos, $href);
}

if (empty($videos)) {
  die("Not Found");
}

header('Content-Type: application/json');
echo json_encode($videos, JSON_PRETTY_PRINT);