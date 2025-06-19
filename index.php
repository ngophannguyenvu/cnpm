<?php
session_start();

require_once 'app/config/database.php';
require_once 'app/helpers/SessionHelper.php';

// Require các Controller API


// Require các Controller thường (giao diện nếu có)

// ... thêm các controller khác nếu cần

// Lấy URL
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// API routing
if ($url[0] === 'api' && isset($url[1])) {
    $apiControllerName = ucfirst($url[1]) . 'ApiController';
    $filePath = 'app/controllers/' . $apiControllerName . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
        $controller = new $apiControllerName();
        $method = $_SERVER['REQUEST_METHOD'];
        $id = $url[2] ?? null;

        // Xác định hành động dựa trên method
        switch ($method) {
            case 'GET':
                $action = $id ? 'show' : 'index';
                break;
            case 'POST':
                // Nếu có action đặc biệt ở URL thứ 2 (ví dụ: /api/account/login)
                $specialAction = $url[2] ?? null;
                if ($specialAction && method_exists($controller, $specialAction)) {
                    $action = $specialAction;
                    $id = null;
                } else {
                    $action = 'store';
                }
                break;
            case 'PUT':
                $action = $id ? 'update' : null;
                break;
            case 'DELETE':
                $action = $id ? 'destroy' : null;
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
                exit;
        }

        if ($action && method_exists($controller, $action)) {
            if ($id) {
                call_user_func_array([$controller, $action], [$id]);
            } else {
                call_user_func_array([$controller, $action], []);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Action not found']);
        }
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'API Controller not found']);
        exit;
    }
}

// Controller thông thường (không phải API)
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

if (file_exists('app/controllers/' . $controllerName . '.php')) {
    require_once 'app/controllers/' . $controllerName . '.php';
    $controller = new $controllerName();
} else {
    die('Controller not found');
}

if (method_exists($controller, $action)) {
    call_user_func_array([$controller, $action], array_slice($url, 2));
} else {
    die('Action not found');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ CNPM</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100vh;
            background: #222;
            color: #fff;
            padding-top: 30px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.3em;
            letter-spacing: 2px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 15px 30px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .sidebar ul li:hover, .sidebar ul li.active {
            background: #444;
        }
        .main-content {
            margin-left: 220px;
            padding: 40px 30px;
            min-height: 100vh;
            background: #fff;
        }
        @media (max-width: 700px) {
            .sidebar { width: 100px; }
            .main-content { margin-left: 100px; }
            .sidebar ul li { padding: 10px 10px; font-size: 0.9em; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>CNPM</h2>
        <ul id="menu">
            <li class="active" data-view="home">Trang chủ</li>
            <li data-view="chitietdichvu">Chi tiết dịch vụ</li>
            <li data-view="danhgia">Đánh giá</li>
            <li data-view="datlich">Đặt lịch</li>
            <li data-view="phong">Phòng</li>
            <li data-view="hoadon">Hóa đơn & Thanh toán</li>
            <li data-view="user">Người dùng</li>
            <li data-view="quangcao">Quảng cáo</li>
            <li data-view="trangthai">Trạng thái</li>
            <li data-view="trangthaiphong">Trạng thái phòng</li>
            <li data-view="phuongthuc">Phương thức</li>
        </ul>
    </div>
    <div class="main-content" id="main-content">
        <!-- Nội dung động sẽ được load ở đây -->
        <h1>Chào mừng đến với hệ thống CNPM!</h1>
        <p>Chọn chức năng ở cột bên trái để bắt đầu sử dụng.</p>
    </div>
    <script>
        const menu = document.getElementById('menu');
        const mainContent = document.getElementById('main-content');
        menu.addEventListener('click', function(e) {
            if (e.target.tagName === 'LI') {
                document.querySelectorAll('#menu li').forEach(li => li.classList.remove('active'));
                e.target.classList.add('active');
                const view = e.target.getAttribute('data-view');
                loadView(view);
            }
        });
        function loadView(view) {
            if (view === 'home') {
                mainContent.innerHTML = `<h1>Chào mừng đến với hệ thống CNPM!</h1><p>Chọn chức năng ở cột bên trái để bắt đầu sử dụng.</p>`;
                return;
            }
            fetch('views/' + view + '/index.php')
                .then(res => {
                    if (!res.ok) throw new Error('Không tìm thấy view!');
                    return res.text();
                })
                .then(html => {
                    mainContent.innerHTML = html;
                })
                .catch(err => {
                    mainContent.innerHTML = '<p style="color:red">Không tìm thấy trang hoặc có lỗi khi tải view.</p>';
                });
        }
        function loadSubView(view, sub) {
            fetch(`views/${view}/${sub}.php`)
                .then(res => res.text())
                .then(html => {
                    mainContent.innerHTML = html + '<br><button class="btn-back">Quay lại</button>';
                });
        }
        // Gán event delegation cho mainContent một lần duy nhất
        mainContent.addEventListener('click', function(e) {
            const currentView = document.querySelector('#menu li.active').getAttribute('data-view');
            if (e.target.classList.contains('btn-add')) {
                loadSubView(currentView, 'add');
            }
            if (e.target.classList.contains('btn-edit')) {
                loadSubView(currentView, 'edit');
            }
            if (e.target.classList.contains('btn-delete')) {
                loadSubView(currentView, 'delete');
            }
            if (e.target.classList.contains('btn-detail')) {
                loadSubView(currentView, 'detail');
            }
            if (e.target.classList.contains('btn-back')) {
                loadView(currentView);
            }
        });
    </script>
</body>
</html>
