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
    <title>Lịch Sử Đặt Lịch - Spa & Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #E84393; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .navbar-brand { color: var(--primary-color) !important; font-weight: 700; }
        .history-container { max-width: 900px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .history-item { border-bottom: 1px solid #eee; padding: 1rem 0; }
        .history-item:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/cnpm/customer">SPA & BEAUTY</a>
            <a href="/cnpm/customer" class="btn btn-light">Trở về</a>
        </div>
    </nav>
    <div class="history-container">
        <h2 class="mb-4 text-center">Lịch Sử Đặt Lịch</h2>
        <div id="history-list">
            <!-- Lịch sử sẽ được tải bằng JavaScript -->
            <p class="text-center">Đang tải lịch sử của bạn...</p>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/cnpm/api/datlich/history')
        .then(res => res.json())
        .then(data => {
            const listDiv = document.getElementById('history-list');
            if (data.success) {
                if (data.data.length > 0) {
                    listDiv.innerHTML = ''; // Xóa thông báo đang tải
                    data.data.forEach(item => {
                        const date = new Date(item.Thoigiandatlich).toLocaleString('vi-VN');
                        const services = item.services.map(s => `<li>${s.Tendichvu} (${parseInt(s.Gia).toLocaleString('vi-VN')} VNĐ)</li>`).join('');
                        
                        const itemDiv = document.createElement('div');
                        itemDiv.className = 'history-item';
                        itemDiv.innerHTML = `
                            <h5>Ngày đặt: ${date}</h5>
                            <p><strong>Trạng thái/Ghi chú:</strong> ${item.Trangthai_ || 'Không có'}</p>
                            <p><strong>Các dịch vụ đã đặt:</strong></p>
                            <ul>${services}</ul>
                        `;
                        listDiv.appendChild(itemDiv);
                    });
                } else {
                    listDiv.innerHTML = '<p class="text-center">Bạn chưa có lịch sử đặt lịch nào.</p>';
                }
            } else {
                listDiv.innerHTML = `<p class="text-center text-danger">${data.error}</p>`;
            }
        });
});
</script>
</body>
</html> 