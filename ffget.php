<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require './../../vendor/autoload.php';

$ffmpeg = FFMpeg\FFMpeg::create(array(
    'ffmpeg.binaries'  => getcwd().'/'.'ffmpeg',
    'ffprobe.binaries' => getcwd().'/'.'ffprobe',
    'timeout'          => 3600,
    'ffmpeg.threads'   => 12,
));
$video = $ffmpeg->open('https://hw14.cdn.asset.aparat.com/aparat-video/3dca0f2ea6e4707bfc534d2cd0899db023225996-480p.mp4');
$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(3))
    ->save('frametest.jpg');
echo "ddd";