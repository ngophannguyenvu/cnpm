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
    <title>Đánh Giá Của Tôi - Spa & Beauty</title>
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
        .rating-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
            padding: 32px 24px 24px 24px;
        }
        .rating-title {
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
        .rating-item {
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: box-shadow 0.3s;
        }
        .rating-item:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .rating-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .rating-stars {
            color: #ffc107;
            font-size: 1.2rem;
        }
        .rating-date {
            color: #666;
            font-size: 0.9rem;
        }
        .rating-comment {
            color: #333;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .rating-invoice {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9rem;
            color: #666;
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
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), #d83682);
            color: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stats-label {
            font-size: 1rem;
            opacity: 0.9;
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
    
    <div class="rating-container">
        <a href="/cnpm/customer" class="back-btn">
            <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
        </a>
        
        <div class="rating-title">
            <i class="fas fa-star me-3"></i>Đánh Giá Của Tôi
        </div>
        
        <div id="rating-stats" class="stats-card" style="display: none;">
            <div class="stats-number" id="total-ratings">0</div>
            <div class="stats-label">Tổng số đánh giá</div>
        </div>
        
        <div id="rating-list">
            <div class="loading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
                <p class="mt-3">Đang tải đánh giá...</p>
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

// Hàm tạo HTML cho sao
function createStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star"></i>';
        } else {
            stars += '<i class="far fa-star"></i>';
        }
    }
    return stars;
}

// Hàm tải dữ liệu đánh giá
function loadRatings() {
    fetch('/cnpm/api/danhgia/index.php?action=user_ratings', {
        method: 'GET',
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                alert('Bạn cần đăng nhập để xem đánh giá');
                window.location.href = '/cnpm/views/user/login.php';
                return;
            }
            throw new Error('Lỗi kết nối');
        }
        return response.json();
    })
    .then(data => {
        const listDiv = document.getElementById('rating-list');
        const statsDiv = document.getElementById('rating-stats');
        
        if (data.success && data.data && data.data.length > 0) {
            // Hiển thị thống kê
            document.getElementById('total-ratings').textContent = data.data.length;
            statsDiv.style.display = 'block';
            
            // Hiển thị danh sách đánh giá
            listDiv.innerHTML = data.data.map(item => {
                const date = formatDate(item.Ngaydanhgia);
                const stars = createStars(parseInt(item.Danhgiasao));
                
                return `
                    <div class="rating-item">
                        <div class="rating-header">
                            <div class="rating-stars">
                                ${stars}
                                <span class="ms-2">${item.Danhgiasao}/5</span>
                            </div>
                            <div class="rating-date">
                                <i class="fas fa-calendar me-1"></i>${date}
                            </div>
                        </div>
                        <div class="rating-comment">
                            <i class="fas fa-comment me-2"></i>${item.Nhanxet}
                        </div>
                        <div class="rating-invoice">
                            <i class="fas fa-receipt me-2"></i>Mã hóa đơn: ${item.MaHD}
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            listDiv.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <h4>Chưa có đánh giá nào</h4>
                    <p>Bạn chưa đánh giá dịch vụ nào trong hệ thống.</p>
                    <a href="/cnpm/customer/lichsu" class="btn btn-primary">
                        <i class="fas fa-history me-2"></i>Xem lịch sử đặt lịch
                    </a>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        document.getElementById('rating-list').innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>Có lỗi xảy ra</h4>
                <p>Không thể tải đánh giá. Vui lòng thử lại sau.</p>
                <button onclick="loadRatings()" class="btn btn-primary">
                    <i class="fas fa-redo me-2"></i>Thử lại
                </button>
            </div>
        `;
    });
}

// Tải dữ liệu khi trang load
document.addEventListener('DOMContentLoaded', function() {
    loadRatings();
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