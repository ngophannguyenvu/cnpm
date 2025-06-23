<?php
// Lấy action từ path nếu có dạng /api/danhgia/customer_rate
$action = $_GET['action'] ?? null;
if (!$action && isset($_SERVER['PATH_INFO'])) {
    $parts = explode('/', trim($_SERVER['PATH_INFO'], '/'));
    $action = $parts[0] ?? null;
} elseif (!$action && isset($_SERVER['REQUEST_URI'])) {
    // fallback cho trường hợp không có PATH_INFO
    $uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
    $parts = explode('/', trim($uri, '/'));
    $apiIndex = array_search('danhgia', $parts);
    if ($apiIndex !== false && isset($parts[$apiIndex + 1])) {
        $action = $parts[$apiIndex + 1];
    }
}
if ($action) $_GET['action'] = $action;

require_once $_SERVER['DOCUMENT_ROOT'] . '/cnpm/app/controllers/DanhGiaApiController.php';

$controller = new DanhGiaApiController();
$controller->handleRequest();
?> 