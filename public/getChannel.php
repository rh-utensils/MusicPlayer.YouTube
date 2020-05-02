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

$urlParts = explode('/', $url);

if (in_array('channel', $urlParts)) {
  $channel = $urlParts[array_search('channel', $urlParts) + 1];

  $url = 'https://www.youtube.com/channel/'.$channel;
} else if (in_array('user', $urlParts)) {
  $user = $urlParts[array_search('user', $urlParts) + 1];

  $url = 'https://www.youtube.com/user/'.$user;
} else {
  die("Not Found");
}

$url = $url.'/videos?live_view=500&flow=list&sort=dd&view=0';
$matches = false;
$next = $content = ''; 

do {
  if ($matches) {
    $obj = json_decode(file_get_contents('https://www.youtube.com'.$matches[1]));
    $next = $obj->content_html;
    $next .= $obj->load_more_widget_html;
  }
  else $next = file_get_contents($url);
  if ($next) $content .= $next;
  else break;
} while (preg_match('|data-uix-load-more-href="([^"]*)"|msiU', $next, $matches));

preg_match_all('|<span class="video-time([^>]*)>(.*)</span>|msiU', $content, $times);
$count = preg_match_all('|<h3 class="yt-lockup-title ([^>]*)>(.*)</h3>|msiU', $content, $matches);
$videos = [];

for($i=0; $i < $count; $i++) {
  if (!preg_match('|<a([^>]*)>([^<]*)</a>|msi',$matches[0][$i],$link)) continue;
  if (!preg_match('|href="(/watch[^"]*)"|i',$link[1],$href)) continue;

  $href = 'https://www.youtube.com'.trim($href[1]);

  array_push($videos, $href);
}

if (empty($videos)) {
  die("Not Found");
}

header('Content-Type: application/json');
echo json_encode($videos, JSON_PRETTY_PRINT);
