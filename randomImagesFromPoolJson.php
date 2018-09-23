<?php

$cache_file = 'cache.json';
$cache_seconds = 60 * 60; // change once per hour
$max_images_count = 50;

if (file_exists($cache_file) && (filemtime($cache_file) > (time() - $cache_seconds ))) {
   if (!isset($_GET['dogs'])) {
      // Cache file is less than five minutes old. 
      // Don't bother refreshing, just use the file as-is.
      echo file_get_contents($cache_file);
      exit;
   }
}

$folder = './pool/';
$images = scandir($folder);
array_shift($images); // remove "." and ".."
array_shift($images);

$images = array_map(function($uri)use($folder) {
    $uri = $folder.$uri;
    
    list($width, $height) = getimagesize($uri);
    
    return [
        'uri'    => $uri,
        'size'   => filesize($uri),
    ]+compact('width','height');
},$images);

$images = array_map(function($arr) {
    // if an image is large, reduce the probability that
    // it will be included porportionally to it's size
    if (90000 /* 90kb */ < mt_rand(0, $arr['size'])) {
    	return null;
    }
    
    return $arr;
}, $images);

$images = array_filter($images);

shuffle($images);

$images = array_splice($images, 0, $max_images_count);

$count = count($images);

// send the right headers
//header("Content-Type: javascript/json");

$data = compact('count', 'images');

$data = json_encode($data);

file_put_contents($cache_file, $data, LOCK_EX);

echo file_get_contents($cache_file);

exit;
?>
