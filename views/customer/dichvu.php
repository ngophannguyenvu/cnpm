<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/cnpm/';
require_once $rootPath . 'app/helpers/SessionHelper.php';
$user = SessionHelper::getUser();
if (!$user) { header('Location: /cnpm/views/user/login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khám Phá Dịch Vụ - Spa & Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #E84393; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .navbar-brand { color: var(--primary-color) !important; font-weight: 700; }
        .service-container { max-width: 1000px; margin: 2rem auto; }
        .service-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }
        .service-card:hover { transform: translateY(-5px); }
        .service-card .card-body { padding: 2rem; }
        .service-card .card-title { color: var(--primary-color); font-weight: 600; }
        .service-card .card-price { font-size: 1.5rem; font-weight: 700; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/cnpm/customer">SPA & BEAUTY</a>
            <a href="/cnpm/customer" class="btn btn-light">Trở về</a>
        </div>
    </nav>
    <div class="container service-container">
        <h2 class="mb-4 text-center">Dịch Vụ Của Chúng Tôi</h2>
        <div id="service-list" class="row">
            <!-- Dịch vụ sẽ được tải bằng JavaScript -->
            <p class="text-center">Đang tải danh sách dịch vụ...</p>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/cnpm/api/dichvu')
        .then(res => res.json())
        .then(services => {
            const listDiv = document.getElementById('service-list');
            if (services.length > 0) {
                listDiv.innerHTML = '';
                services.forEach(service => {
                    const price = parseInt(service.Gia).toLocaleString('vi-VN');
                    const serviceCol = document.createElement('div');
                    serviceCol.className = 'col-md-6 col-lg-4';
                    serviceCol.innerHTML = `
                        <div class="card service-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">${service.Tendichvu}</h5>
                                <p class="card-text">${service.MoTa || 'Chưa có mô tả'}</p>
                                <p class="card-price text-primary">${price} VNĐ</p>
                                <a href="/cnpm/customer/datlich" class="btn btn-primary">Đặt dịch vụ này</a>
                            </div>
                        </div>
                    `;
                    listDiv.appendChild(serviceCol);
                });
            } else {
                listDiv.innerHTML = '<p class="text-center">Hiện chưa có dịch vụ nào.</p>';
            }
        });
});
</script>
</body>
</html> 