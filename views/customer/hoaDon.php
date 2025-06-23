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
    <title>Lịch Sử Hóa Đơn - Spa & Beauty</title>
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
        .invoice-container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
            padding: 32px 24px 24px 24px;
        }
        .invoice-title {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: bold;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .invoice-table th, .invoice-table td {
            padding: 12px 16px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .invoice-table th {
            background: var(--primary-color);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .invoice-table tr:hover {
            background: var(--light-pink);
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
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

    <div class="invoice-container">
        <a href="/cnpm/customer" class="back-btn">
            <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
        </a>
        
        <div class="invoice-title">
            <i class="fas fa-file-invoice-dollar me-3"></i>Lịch Sử Hóa Đơn
        </div>
        
        <div id="invoice-content">
            <div class="loading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
                <p class="mt-3">Đang tải lịch sử hóa đơn...</p>
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
                case 'đã thanh toán':
                case 'paid':
                    return 'status-paid';
                case 'chờ thanh toán':
                case 'pending':
                    return 'status-pending';
                case 'đã hủy':
                case 'cancelled':
                    return 'status-cancelled';
                default:
                    return 'status-pending';
            }
        }

        // Hàm tải dữ liệu hóa đơn
        function loadInvoices() {
            fetch('/cnpm/api/hoaDonVaThanhToan/userInvoices', {
                method: 'POST',
                credentials: 'include'
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 401) {
                        alert('Bạn cần đăng nhập để xem lịch sử hóa đơn');
                        window.location.href = '/cnpm/views/user/login.php';
                        return;
                    }
                    throw new Error('Lỗi kết nối');
                }
                return response.json();
            })
            .then(data => {
                const content = document.getElementById('invoice-content');
                
                if (data.success && data.data && data.data.length > 0) {
                    // Hiển thị bảng hóa đơn
                    content.innerHTML = `
                        <div class="table-responsive">
                            <table class="invoice-table">
                                <thead>
                                    <tr>
                                        <th>Mã Hóa Đơn</th>
                                        <th>Ngày Lập</th>
                                        <th>Tổng Tiền</th>
                                        <th>Phương Thức</th>
                                        <th>Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.data.map(invoice => `
                                        <tr>
                                            <td><strong>#${invoice.MaHD}</strong></td>
                                            <td>${formatDate(invoice.NgayThanhToan)}</td>
                                            <td><strong>${formatCurrency(invoice.Tongtien)}</strong></td>
                                            <td>${invoice.TenPhuongThuc || 'Không xác định'}</td>
                                            <td>
                                                <span class="status-badge ${getStatusClass(invoice.TenTrangThai)}">
                                                    ${invoice.TenTrangThai || 'Không xác định'}
                                                </span>
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                Hiển thị ${data.data.length} hóa đơn của bạn
                            </p>
                        </div>
                    `;
                } else {
                    // Hiển thị trạng thái trống
                    content.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-file-invoice"></i>
                            <h4>Chưa có hóa đơn nào</h4>
                            <p>Bạn chưa có hóa đơn nào trong hệ thống.</p>
                            <a href="/cnpm/customer/datlich" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Đặt lịch ngay
                            </a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                document.getElementById('invoice-content').innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>Có lỗi xảy ra</h4>
                        <p>Không thể tải lịch sử hóa đơn. Vui lòng thử lại sau.</p>
                        <button onclick="loadInvoices()" class="btn btn-primary">
                            <i class="fas fa-redo me-2"></i>Thử lại
                        </button>
                    </div>
                `;
            });
        }

        // Tải dữ liệu khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            loadInvoices();
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