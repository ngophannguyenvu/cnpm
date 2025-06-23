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
        .history-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
            padding: 32px 24px 24px 24px;
        }
        .history-title {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: bold;
        }
        .back-btn {
            background: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: #d83682;
            color: #fff;
            text-decoration: none;
        }
        .history-item {
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: box-shadow 0.3s;
        }
        .history-item:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .history-item h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 15px;
        }
        .history-item p {
            margin-bottom: 10px;
            color: #666;
        }
        .history-item ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        .history-item li {
            color: #333;
            margin-bottom: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-left: 10px;
        }
        .status-waiting {
            background: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        .status-completed {
            background: #cce5ff;
            color: #004085;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .spinner-border {
            color: var(--primary-color);
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
    
    <div class="history-container">
        <a href="/cnpm/customer" class="back-btn">
            <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
        </a>
        
        <div class="history-title">
            <i class="fas fa-history me-3"></i>Lịch Sử Đặt Lịch
        </div>
        
        <div id="history-list">
            <div class="loading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
                <p class="mt-3">Đang tải lịch sử đặt lịch...</p>
            </div>
        </div>
    </div>

<script>
// Hàm format ngày tháng
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Hàm format tiền tệ
function formatCurrency(amount) {
    if (!amount) return '0đ';
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Hàm lấy class cho status badge
function getStatusClass(status) {
    switch(status?.toLowerCase()) {
        case 'đang chờ':
        case 'waiting':
            return 'status-waiting';
        case 'xác nhận':
        case 'confirmed':
            return 'status-confirmed';
        case 'đã hoàn thành':
        case 'completed':
            return 'status-completed';
        default:
            return 'status-waiting';
    }
}

// Hàm tải dữ liệu lịch sử
function loadHistory() {
    fetch('/cnpm/api/datlich/history', {
        method: 'POST',
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                alert('Bạn cần đăng nhập để xem lịch sử đặt lịch');
                window.location.href = '/cnpm/views/user/login.php';
                return;
            }
            throw new Error('Lỗi kết nối');
        }
        return response.json();
    })
    .then(data => {
        const listDiv = document.getElementById('history-list');
        
        if (data.success && data.data && data.data.length > 0) {
            listDiv.innerHTML = data.data.map(item => {
                const date = formatDate(item.Thoigiandatlich);
                const services = item.services.map(s => 
                    `<li>${s.Tendichvu} (${formatCurrency(s.Gia)})</li>`
                ).join('');
                
                return `
                    <div class="history-item">
                        <h5>
                            <i class="fas fa-calendar-check me-2"></i>
                            Ngày đặt: ${date}
                            <span class="status-badge ${getStatusClass(item.Trangthai_)}">
                                ${item.Trangthai_ || 'Không xác định'}
                            </span>
                        </h5>
                        <p><strong>Các dịch vụ đã đặt:</strong></p>
                        <ul>${services}</ul>
                    </div>
                `;
            }).join('');
            
            // Thêm thông tin tổng quan
            listDiv.innerHTML += `
                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Hiển thị ${data.data.length} lịch hẹn của bạn
                    </p>
                </div>
            `;
        } else {
            listDiv.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Chưa có lịch sử đặt lịch</h4>
                    <p>Bạn chưa có lịch hẹn nào trong hệ thống.</p>
                    <a href="/cnpm/customer/datlich" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Đặt lịch ngay
                    </a>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        document.getElementById('history-list').innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>Có lỗi xảy ra</h4>
                <p>Không thể tải lịch sử đặt lịch. Vui lòng thử lại sau.</p>
                <button onclick="loadHistory()" class="btn btn-primary">
                    <i class="fas fa-redo me-2"></i>Thử lại
                </button>
            </div>
        `;
    });
}

// Tải dữ liệu khi trang load
document.addEventListener('DOMContentLoaded', function() {
    loadHistory();
});

// Xử lý đăng xuất
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