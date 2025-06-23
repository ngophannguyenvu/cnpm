<?php
// Bắt đầu session và lấy thông tin người dùng
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Giả lập require_once vì nó sẽ được gọi từ controller
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/cnpm/';
require_once $rootPath . 'app/helpers/SessionHelper.php';
$user = SessionHelper::getUser();

// Nếu chưa đăng nhập, chuyển về trang đăng nhập
if (!$user) {
    header('Location: /cnpm/views/user/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Khách Hàng - Spa & Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E84393;
            --light-pink: #fdecf4;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
        }
        .hero-section {
            background-color: var(--light-pink);
            border: 1px solid #fce1ee;
            color: #6c1d3e;
        }
        .hero-section .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 30px;
            font-weight: 600;
        }
        .hero-section .btn-primary:hover {
            background-color: #d83682;
            border-color: #d83682;
        }
        .feature-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #eee;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .feature-card .text-primary {
            color: var(--primary-color) !important;
        }
        .feature-card .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
        }
        .feature-card .btn-outline-primary:hover {
            color: #fff;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/cnpm/customer">SPA & BEAUTY</a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    Xin chào, <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                </span>
                <button id="logout-btn-customer" class="btn btn-outline-danger">Đăng xuất</button>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <div class="hero-section text-center p-5 mb-4 rounded-3">
            <h1 class="display-5 fw-bold">Chào mừng trở lại!</h1>
            <p class="fs-4">Chúng tôi sẵn sàng phục vụ bạn. Bạn muốn làm gì hôm nay?</p>
            <a href="/cnpm/customer/datlich" class="btn btn-primary btn-lg">Đặt lịch ngay</a>
        </div>

        <h2 class="text-center mb-4">Bảng điều khiển của bạn</h2>
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center feature-card">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Đặt Lịch Hẹn</h5>
                        <p class="card-text">Tìm thời gian phù hợp và đặt dịch vụ bạn yêu thích.</p>
                        <a href="/cnpm/customer/datlich" class="btn btn-outline-primary mt-auto">Đặt ngay</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center feature-card">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-history fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Lịch Sử Đặt Lịch</h5>
                        <p class="card-text">Xem lại các cuộc hẹn và dịch vụ đã sử dụng.</p>
                        <a href="/cnpm/customer/lichsu" class="btn btn-outline-primary mt-auto">Xem lịch sử</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center feature-card">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-spa fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Dịch Vụ Của Chúng Tôi</h5>
                        <p class="card-text">Khám phá tất cả các dịch vụ chăm sóc sắc đẹp tuyệt vời.</p>
                        <a href="/cnpm/customer/dichvu" class="btn btn-outline-primary mt-auto">Khám phá</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center feature-card">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-user-edit fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Hồ Sơ Của Bạn</h5>
                        <p class="card-text">Cập nhật thông tin cá nhân và mật khẩu.</p>
                        <a href="/cnpm/customer/hoso" class="btn btn-outline-primary mt-auto">Cập nhật</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

<script>
document.getElementById('logout-btn-customer').onclick = function() {
    fetch('/cnpm/api/user/logout', {
        method: 'POST',
        credentials: 'include'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Đăng xuất thành công!');
            location.href = '/cnpm/';
        }
    })
    .catch(err => console.error(err));
};
</script>

</body>
</html> 