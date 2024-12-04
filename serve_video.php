<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Check if the user is logged in and has access to the video
if (!is_logged_in()) {
    die("Access denied");
}

if (!isset($_GET['video'])) {
    die("No video specified");
}

$video_path = urldecode($_GET['video']);
$full_path = __DIR__ . '/' . $video_path;

if (!file_exists($full_path)) {
    die("Video file not found");
}

$file_extension = strtolower(pathinfo($full_path, PATHINFO_EXTENSION));
$content_type = 'video/mp4';
if ($file_extension === 'webm') {
    $content_type = 'video/webm';
} elseif ($file_extension === 'ogg') {
    $content_type = 'video/ogg';
}

header("Content-Type: $content_type");
header("Content-Length: " . filesize($full_path));
header("Accept-Ranges: bytes");

readfile($full_path);
exit;