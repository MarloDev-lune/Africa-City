<?php
session_start();

$max_requests = 100; // عدد الطلبات المسموح بها
$time_window = 60; // خلال كم ثانية

if (!isset($_SESSION['requests'])) {
    $_SESSION['requests'] = [];
}

// حذف الطلبات القديمة
$_SESSION['requests'] = array_filter($_SESSION['requests'], function ($timestamp) use ($time_window) {
    return (time() - $timestamp) < $time_window;
});

if (count($_SESSION['requests']) >= $max_requests) {
    http_response_code(429); // Too Many Requests
    die("Blocked due to too many requests.");
}

// حفظ الوقت الحالي
$_SESSION['requests'][] = time();

echo "OK";
?>
