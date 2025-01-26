<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!is_logged_in()) {
    die("Access denied");
}

if (!isset($_GET['video'])) {
    die("No video specified");
}

$video_path = urldecode($_GET['video']);
$full_path = __DIR__ . '/' . $video_path;

if (!file_exists($full_path)) {
    die("Video file not found: $full_path");
}


error_log("Serving video: $full_path");

$file_extension = strtolower(pathinfo($full_path, PATHINFO_EXTENSION));
$content_type = match ($file_extension) {
    'webm' => 'video/webm',
    'ogg' => 'video/ogg',
    default => 'video/mp4',
};

header("Content-Type: $content_type");
header("Accept-Ranges: bytes");

if (isset($_SERVER['HTTP_RANGE'])) {
    $range = $_SERVER['HTTP_RANGE'];
    list(, $range) = explode('=', $range, 2);
    list($start, $end) = explode('-', $range);
    $start = intval($start);
    $end = $end ? intval($end) : filesize($full_path) - 1;
    $length = $end - $start + 1;
    header("HTTP/1.1 206 Partial Content");
    header("Content-Range: bytes $start-$end/" . filesize($full_path));
    header("Content-Length: $length");
    $file = fopen($full_path, 'rb');
    fseek($file, $start);
    echo fread($file, $length);
    fclose($file);
} else {
    header("Content-Length: " . filesize($full_path));
    readfile($full_path);
}
exit;
