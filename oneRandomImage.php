<?php

$folder = './pool/';
$images = scandir($folder);
array_shift($images); // remove "." and ".."
array_shift($images);

$filename = $folder.$images[array_rand($images,1)];

$fp = fopen($filename, 'rb');
// send the right headers
header("Content-Type: image/png"); //browser seems to be ok with receiving gif/jpg under the png name
header("Content-Length: " . filesize($filename));

fpassthru($fp);
