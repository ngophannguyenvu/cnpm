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
    // Special route for dashboard
    if ($url[1] === 'dashboard' && isset($url[2]) && $url[2] === 'stats') {
        require_once 'app/controllers/DashboardApiController.php';
        //$controller = new DashboardApiController();
        $controller->getStats();
        exit;
    }

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
    <title>Spa - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        :root {
            --primary-color: #E84393;
            --secondary-color: #F5F7FA;
            --sidebar-bg: #1E222D;
            --text-color-light: #fefefe;
            --text-color-dark: #333;
            --card-bg: #fff;
            --border-color: #EFEFEF;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--secondary-color);
            color: var(--text-color-dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100%;
            background: var(--sidebar-bg);
            color: var(--text-color-light);
            display: flex;
            flex-direction: column;
            padding: 20px 0;
        }
        .sidebar-header {
            padding: 0 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .sidebar-header h2 {
            font-size: 1.8em;
            margin: 0;
            font-weight: 600;
        }
        .sidebar-header h2 .r-logo {
            color: var(--primary-color);
            background: var(--text-color-light);
            border-radius: 5px;
            padding: 0 5px;
            margin-right: 5px;
        }
        .sidebar .menu {
            list-style: none;
            padding: 0;
            flex-grow: 1;
        }
        .sidebar .menu li {
            padding: 15px 25px;
            cursor: pointer;
            transition: background 0.3s, border-left 0.3s;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
        }
        .sidebar .menu li i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        .sidebar .menu li:hover, .sidebar .menu li.active {
            background: rgba(255, 255, 255, 0.05);
            border-left-color: var(--primary-color);
        }
        .sidebar .menu li a {
            color: var(--text-color-light);
            text-decoration: none;
            font-size: 0.95em;
        }
        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 1.5em;
            margin: 0;
        }
        .kpi-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .card .card-title {
            font-size: 0.9em;
            color: #888;
            margin-bottom: 10px;
        }
        .card .card-value {
            font-size: 2em;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .card .card-icon {
            font-size: 2.5em;
            color: var(--primary-color);
            opacity: 0.7;
        }
         .card-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chart-container {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .menu .has-submenu { position: relative; }
        .menu .has-submenu > ul.submenu {
            background: #23263a;
            border-radius: 6px;
            margin-top: 6px;
        }
        .menu .has-submenu > ul.submenu li:hover {
            background: rgba(255,255,255,0.07);
        }
        .menu .has-submenu.active > ul.submenu {
            display: block !important;
        }
        .menu .has-submenu > ul.submenu {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><span class="r-logo">R</span> Spa</h2>
            </div>
            <ul class="menu" id="menu">
                <li class="active" data-view="tongquan"><i class="fas fa-tachometer-alt"></i>Tổng quan</li>
                <li data-view="user"><i class="fas fa-users"></i>Quản lý người dùng</li>
                <li data-view="chitietdichvu"><i class="fas fa-concierge-bell"></i>Quản lý dịch vụ</li>
                <li data-view="dichvu"><i class="fas fa-spa"></i>Dịch vụ</li>
                <li data-view="phong"><i class="fas fa-door-open"></i>Quản lý phòng</li>
                <li data-view="phuongthuc"><i class="fas fa-credit-card"></i>Phương thức thanh toán</li>
                <li data-view="quangcao"><i class="fas fa-bullhorn"></i>Quản lý quảng cáo</li>
                <li data-view="hoadon"><i class="fas fa-file-invoice-dollar"></i>Quản lý tài chính</li>
                <li data-view="danhgia"><i class="fas fa-star"></i>Quản lý đánh giá</li>
                <li data-view="datlich"><i class="fas fa-calendar-check"></i>Quản lý đặt lịch</li>
                <li class="has-submenu" id="menu-trangthai">
                    <i class="fas fa-toggle-on"></i>Quản lý trạng thái
                    <ul class="submenu" style="display:none; list-style:none; padding-left:20px; margin:0;">
                        <li data-view="trangthai" style="padding:10px 0; cursor:pointer;"><i class="fas fa-circle"></i> Trạng thái</li>
                        <li data-view="trangthaiphong" style="padding:10px 0; cursor:pointer;"><i class="fas fa-door-closed"></i> Trạng thái phòng</li>
                    </ul>
                </li>
                <li><i class="fas fa-sign-out-alt"></i>Đăng xuất</li>
            </ul>
        </div>
        <div class="main-content" id="main-content">
            <!-- Dynamic content will be loaded here -->
        </div>
    </div>
    <script>
        const menu = document.getElementById('menu');
        const mainContent = document.getElementById('main-content');
        const defaultView = 'tongquan';

        // Function to load main dashboard view
        function loadDashboard() {
            mainContent.innerHTML = `
                <div class="header">
                    <h1>Chào mừng đến với Spa Admin!</h1>
                </div>
                <div class="kpi-cards">
                    <div class="card">
                        <div class="card-content">
                            <div>
                                <div class="card-title">TỔNG DOANH THU</div>
                                <div class="card-value" id="total-revenue">0đ</div>
                            </div>
                            <i class="fas fa-money-bill-wave card-icon"></i>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div>
                                <div class="card-title">TỔNG ĐẶT LỊCH</div>
                                <div class="card-value" id="total-bookings">0</div>
                            </div>
                            <i class="fas fa-calendar-alt card-icon"></i>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                             <div>
                                <div class="card-title">TỔNG KHÁCH HÀNG</div>
                                <div class="card-value" id="total-customers">0</div>
                            </div>
                            <i class="fas fa-user-friends card-icon"></i>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div>
                                <div class="card-title">TỔNG ĐÁNH GIÁ</div>
                                <div class="card-value" id="total-reviews">0</div>
                            </div>
                            <i class="fas fa-star card-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            `;
            fetchDashboardData();
        }

        // Function to fetch data for dashboard
        function fetchDashboardData() {
            // fetch('/api/dashboard/stats')
            //     .then(response => response.json())
            //     .then(data => {
            //         const formatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });
            //         document.getElementById('total-revenue').innerText = formatter.format(data.total_revenue || 0);
            //         document.getElementById('total-bookings').innerText = data.total_bookings || 0;
            //         document.getElementById('total-customers').innerText = data.total_customers || 0;
            //         document.getElementById('total-reviews').innerText = data.total_reviews || 0;
            //     })
            //     .catch(error => {
            //         console.error('Error fetching dashboard data:', error);
            //     });

            // Mock chart (can be replaced with real data later)
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Doanh thu',
                        data: [120000, 190000, 300000, 500000, 200000, 300000, 450000],
                        backgroundColor: 'rgba(232, 67, 147, 0.2)',
                        borderColor: 'rgba(232, 67, 147, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
        
        // Function to load other views
        function loadView(view) {
             if (view === 'tongquan') {
                loadDashboard();
                return;
            }
            fetch('views/' + view + '/index.php')
                .then(res => {
                    if (!res.ok) throw new Error('Không tìm thấy view!');
                    return res.text();
                })
                .then(html => {
                    mainContent.innerHTML = html;
                    // Re-execute scripts
                    mainContent.querySelectorAll('script').forEach(oldScript => {
                        const newScript = document.createElement('script');
                        if (oldScript.src) newScript.src = oldScript.src;
                        else newScript.textContent = oldScript.textContent;
                        document.body.appendChild(newScript).parentNode.removeChild(newScript);
                    });
                })
                .catch(err => {
                    mainContent.innerHTML = `<p style="color:red; text-align:center;">Lỗi: ${err.message}</p>`;
                });
        }
        
        // Menu click event listener
        menu.addEventListener('click', function(e) {
            const targetLi = e.target.closest('li');
            if (targetLi) {
                document.querySelectorAll('#menu li').forEach(li => li.classList.remove('active'));
                targetLi.classList.add('active');
                const view = targetLi.getAttribute('data-view');
                if(view) {
                    loadView(view);
                } else if (targetLi.innerText.includes('Đăng xuất')) {
                    // Handle logout here
                    console.log("Logout clicked");
                }
            }
        });

        // Xử lý xổ/collapse submenu
        const menuTrangThai = document.getElementById('menu-trangthai');
        if (menuTrangThai) {
            menuTrangThai.addEventListener('click', function(e) {
                // Nếu click vào chính menu cha thì xổ/collapse
                if (e.target === this || e.target.closest('.has-submenu') === this) {
                    this.classList.toggle('active');
                    const submenu = this.querySelector('.submenu');
                    if (submenu) submenu.style.display = this.classList.contains('active') ? 'block' : 'none';
                    e.stopPropagation();
                }
            });
            // Xử lý click vào submenu để chuyển view
            menuTrangThai.querySelectorAll('.submenu li').forEach(function(sub) {
                sub.addEventListener('click', function(e) {
                    document.querySelectorAll('#menu li').forEach(li => li.classList.remove('active'));
                    sub.classList.add('active');
                    const view = sub.getAttribute('data-view');
                    if (view) loadView(view);
                    e.stopPropagation();
                });
            });
        }

        // Initial load
        loadDashboard();

    </script>
</body>
</html>
