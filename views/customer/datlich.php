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
    <title>Đặt Lịch Hẹn - Spa & Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #E84393; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .navbar-brand { color: var(--primary-color) !important; font-weight: 700; }
        .booking-container { max-width: 800px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(232, 67, 147, 0.25); }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/cnpm/customer">SPA & BEAUTY</a>
            <a href="/cnpm/customer" class="btn btn-light">Trở về</a>
        </div>
    </nav>

    <div class="booking-container">
        <h2 class="mb-4 text-center">Đặt Lịch Hẹn</h2>
        <div id="booking-message" class="mb-3"></div>
        <form id="booking-form">
            <div class="mb-3">
                <label for="dichvu" class="form-label">Chọn dịch vụ</label>
                <select class="form-select" id="dichvu" required multiple>
                    <!-- Dịch vụ sẽ được tải bằng JavaScript -->
                </select>
            </div>
            <div class="mb-3">
                <label for="thoigian" class="form-label">Chọn ngày và giờ</label>
                <input type="datetime-local" class="form-control" id="thoigian" required>
            </div>
            <div class="mb-3">
                <label for="ghichu" class="form-label">Ghi chú (tùy chọn)</label>
                <textarea class="form-control" id="ghichu" rows="3"></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Xác nhận đặt lịch</button>
            </div>
        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tải danh sách dịch vụ
    fetch('/cnpm/api/dichvu')
        .then(res => res.json())
        .then(services => {
            const select = document.getElementById('dichvu');
            services.forEach(service => {
                const option = document.createElement('option');
                option.value = service.MaDV;
                option.textContent = `${service.Tendichvu} - ${parseInt(service.Gia).toLocaleString('vi-VN')} VNĐ`;
                select.appendChild(option);
            });
        });
    
    // Xử lý submit form
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const selectedServices = Array.from(document.getElementById('dichvu').selectedOptions).map(opt => opt.value);
        
        const bookingData = {
            dichvu_ids: selectedServices,
            thoigian: document.getElementById('thoigian').value,
            ghichu: document.getElementById('ghichu').value
        };

        fetch('/cnpm/api/datlich/book', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(bookingData)
        })
        .then(res => res.json())
        .then(data => {
            const msgDiv = document.getElementById('booking-message');
            if (data.success) {
                msgDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                document.getElementById('booking-form').reset();
            } else {
                msgDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            }
        });
    });
});
</script>
</body>
</html> 