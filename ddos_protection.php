<?php
session_start();

$limit = 30; // عدد الطلبات المسموح بها
$timeWindow = 60; // خلال كم ثانية (هنا: 60 ثانية)
$ip = $_SERVER['REMOTE_ADDR'];
$time = time();

// ملف التخزين المؤقت
$log_file = __DIR__ . '/ddos_log.json';

// تحميل السجل القديم
$log = file_exists($log_file) ? json_decode(file_get_contents($log_file), true) : [];

// تنظيف السجل من الطلبات القديمة
foreach ($log as $ipAddr => $timestamps) {
    $log[$ipAddr] = array_filter($timestamps, function($t) use ($time, $timeWindow) {
        return ($time - $t) <= $timeWindow;
    });
    if (empty($log[$ipAddr])) {
        unset($log[$ipAddr]);
    }
}

// تحقق من عدد طلبات الـ IP الحالي
if (!isset($log[$ip])) $log[$ip] = [];
$log[$ip][] = $time;

if (count($log[$ip]) > $limit) {
    http_response_code(429); // Too Many Requests
    echo json_encode(["error" => "Too many requests."]);
    exit;
}

// حفظ السجل
file_put_contents($log_file, json_encode($log));

http_response_code(200);
echo json_encode(["status" => "ok"]);
?>
