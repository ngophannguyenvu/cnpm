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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary-color: #E84393;
            --primary-light: #ff80ab;
            --primary-dark: #d83682;
        }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #fdfbfb 0%, #fce1ee 100%);
            min-height: 100vh;
        }
        
        .navbar { 
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(232,67,147,0.08);
        }
        
        .navbar-brand { 
            color: var(--primary-color) !important; 
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .btn-back {
            background: #fff;
            color: var(--primary-color);
            border: 1.5px solid var(--primary-light);
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #ffe4ec;
            color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .page-header {
            text-align: center;
            padding: 60px 0 40px 0;
            animation: fadeInDown 0.8s;
        }

        .page-title {
            color: var(--primary-color);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .page-subtitle {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .service-container { 
            max-width: 1200px; 
            margin: 0 auto 60px auto;
            padding: 0 20px;
        }

        .service-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(232,67,147,0.08);
            margin-bottom: 30px;
            transition: all 0.4s ease;
            overflow: hidden;
            animation: fadeInUp 0.6s;
        }

        .service-card:hover { 
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(232,67,147,0.15);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            color: white;
            font-size: 28px;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .card-body { 
            padding: 30px;
            text-align: center;
        }

        .service-title {
            color: #333;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .service-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
            min-height: 60px;
        }

        .service-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 25px;
            text-shadow: 2px 2px 8px rgba(232,67,147,0.1);
        }

        .btn-book {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 35px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(232,67,147,0.15);
        }

        .btn-book:hover {
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(232,67,147,0.25);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .page-title { font-size: 2rem; }
            .service-card { margin-bottom: 20px; }
            .service-title { font-size: 1.2rem; }
            .service-price { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/cnpm/customer">
                <i class="fas fa-spa me-2"></i>SPA & BEAUTY
            </a>
            <a href="/cnpm/customer" class="btn btn-back">
                <i class="fas fa-arrow-left me-2"></i>Trở về
            </a>
        </div>
    </nav>

    <div class="page-header">
        <h1 class="page-title">Dịch Vụ Của Chúng Tôi</h1>
        <p class="page-subtitle">Khám phá các dịch vụ chăm sóc sắc đẹp cao cấp, được thiết kế riêng để mang đến trải nghiệm thư giãn và làm đẹp tuyệt vời nhất cho bạn.</p>
    </div>

    <div class="container service-container">
        <div id="service-list" class="row">
            <!-- Dịch vụ sẽ được tải bằng JavaScript -->
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Danh sách icon cho các loại dịch vụ
    const serviceIcons = [
        'fa-spa', 'fa-face-smile', 'fa-hand-sparkles', 'fa-heart', 
        'fa-star', 'fa-gem', 'fa-magic-wand-sparkles', 'fa-wand-magic-sparkles'
    ];

    fetch('/cnpm/api/dichvu')
        .then(res => res.json())
        .then(services => {
            const listDiv = document.getElementById('service-list');
            if (services.length > 0) {
                listDiv.innerHTML = '';
                services.forEach((service, index) => {
                    const price = parseInt(service.Gia).toLocaleString('vi-VN');
                    const iconClass = serviceIcons[index % serviceIcons.length];
                    const delay = (index + 1) * 0.1;
                    
                    const serviceCol = document.createElement('div');
                    serviceCol.className = 'col-md-6 col-lg-4';
                    serviceCol.style.animationDelay = `${delay}s`;
                    
                    serviceCol.innerHTML = `
                        <div class="service-card">
                            <div class="card-body">
                                <div class="service-icon">
                                    <i class="fas ${iconClass}"></i>
                                </div>
                                <h3 class="service-title">${service.Tendichvu}</h3>
                                <p class="service-description">${service.MoTa || 'Trải nghiệm dịch vụ cao cấp của chúng tôi.'}</p>
                                <div class="service-price">${price} VNĐ</div>
                                <a href="/cnpm/customer/datlich" class="btn btn-book">
                                    <i class="fas fa-calendar-check me-2"></i>Đặt Ngay
                                </a>
                            </div>
                        </div>
                    `;
                    listDiv.appendChild(serviceCol);
                });
            } else {
                listDiv.innerHTML = `
                    <div class="col-12 text-center mt-5">
                        <i class="fas fa-info-circle fs-1 text-muted mb-3"></i>
                        <p class="text-muted">Hiện chưa có dịch vụ nào.</p>
                    </div>
                `;
            }
        });
});
</script>
</body>
</html> 