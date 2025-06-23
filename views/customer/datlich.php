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
        .navbar-brand { color: var(--primary-color) !important; font-weight: 700; font-size: 1.5rem; letter-spacing: 1px; }
        .btn-back {
            background: #fff;
            color: #e84393;
            border: 1.5px solid #ff80ab;
            border-radius: 8px;
            font-weight: 500;
            padding: 7px 18px;
            margin-left: 10px;
            transition: background 0.18s, color 0.18s;
        }
        .btn-back:hover { background: #ffe4ec; color: #d83682; }
        .booking-container {
            max-width: 900px;
            margin: 48px auto 0 auto;
            background: linear-gradient(120deg, #fff0f6 0%, #fce1ee 100%);
            padding: 38px 32px 32px 32px;
            border-radius: 22px;
            box-shadow: 0 8px 32px rgba(232,67,147,0.10);
            animation: fadeInUp 0.7s;
        }
        .booking-container h2 {
            color: #e84393;
            font-weight: 700;
            font-size: 2.1rem;
            letter-spacing: 1px;
            margin-bottom: 32px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .form-label { color: #e84393; font-weight: 500; }
        .form-select, .form-control {
            border-radius: 8px;
            border: 1.5px solid #ffb6d5;
            font-size: 1.08rem;
            margin-bottom: 8px;
        }
        .form-select:focus, .form-control:focus {
            border-color: #e84393;
            box-shadow: 0 0 0 0.18rem rgba(232, 67, 147, 0.13);
        }
        .btn-confirm {
            background: linear-gradient(90deg, #ff80ab, #ff4081);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 0;
            font-size: 1.15rem;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(232,67,147,0.08);
            transition: background 0.18s, transform 0.18s;
        }
        .btn-confirm:hover {
            background: #e73370;
            transform: translateY(-2px) scale(1.03);
        }
        @media (max-width: 700px) {
            .booking-container { padding: 12px 4px; }
            .booking-container h2 { font-size: 1.2rem; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .service-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .service-card.selected {
            border-color: #e84393;
            background: #fff5f9;
            transform: translateY(-3px);
        }
        .service-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(232,67,147,0.15);
        }
        .service-name {
            color: #333;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }
        .service-price {
            color: #e84393;
            font-weight: 500;
            font-size: 1rem;
        }
        .selected-services {
            background: #fff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1.5px solid #ffb6d5;
        }
        .selected-services-title {
            color: #e84393;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .selected-service-tag {
            display: inline-block;
            background: #ffe4ec;
            color: #e84393;
            padding: 5px 12px;
            border-radius: 20px;
            margin: 0 8px 8px 0;
            font-size: 0.9rem;
        }
        .selected-service-tag .remove-service {
            margin-left: 8px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/cnpm/customer">SPA & BEAUTY</a>
            <a href="/cnpm/customer" class="btn btn-back"><i class="fas fa-arrow-left"></i> Trở về</a>
        </div>
    </nav>

    <div class="booking-container">
        <h2><i class="fas fa-calendar-check"></i> Đặt Lịch Hẹn</h2>
        <div id="booking-message" class="mb-3"></div>
        <form id="booking-form">
            <div class="mb-4">
                <label class="form-label">Chọn dịch vụ</label>
                <div class="services-grid" id="services-grid">
                    <!-- Dịch vụ sẽ được tải bằng JavaScript -->
                
            </div>
            <div class="mb-3">
                <label for="thoigian" class="form-label">Chọn ngày và giờ</label>
                <input type="datetime-local" class="form-control" id="thoigian" required>
            </div>
            <div class="mb-3">
                <label for="ghichu" class="form-label">Ghi chú (tùy chọn)</label>
                <textarea class="form-control" id="ghichu" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-confirm">Xác nhận đặt lịch</button>
        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedServices = new Set();
    
    // Tải danh sách dịch vụ
    fetch('/cnpm/api/dichvu')
        .then(res => res.json())
        .then(services => {
            const grid = document.getElementById('services-grid');
            services.forEach(service => {
                const card = document.createElement('div');
                card.className = 'service-card';
                card.innerHTML = `
                    <div class="service-name">${service.Tendichvu}</div>
                    <div class="service-price">${parseInt(service.Gia).toLocaleString('vi-VN')} VNĐ</div>
                `;
                card.dataset.id = service.MaDV;
                card.dataset.name = service.Tendichvu;
                
                card.addEventListener('click', () => toggleService(card, service));
                grid.appendChild(card);
            });
        });

    function toggleService(card, service) {
        if (selectedServices.has(service.MaDV)) {
            selectedServices.delete(service.MaDV);
            card.classList.remove('selected');
        } else {
            selectedServices.add(service.MaDV);
            card.classList.add('selected');
        }
        updateSelectedServicesTags();
    }

    function updateSelectedServicesTags() {
        const tagsContainer = document.getElementById('selected-services-tags');
        tagsContainer.innerHTML = '';
        
        const cards = document.querySelectorAll('.service-card');
        cards.forEach(card => {
            if (selectedServices.has(card.dataset.id)) {
                const tag = document.createElement('span');
                tag.className = 'selected-service-tag';
                tag.innerHTML = `
                    ${card.dataset.name}
                    <span class="remove-service" data-id="${card.dataset.id}">×</span>
                `;
                tagsContainer.appendChild(tag);
            }
        });

        // Add click handlers for remove buttons
        document.querySelectorAll('.remove-service').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const serviceId = e.target.dataset.id;
                const card = document.querySelector(`.service-card[data-id="${serviceId}"]`);
                if (card) {
                    selectedServices.delete(serviceId);
                    card.classList.remove('selected');
                    updateSelectedServicesTags();
                }
            });
        });
    }
    
    // Xử lý submit form
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const bookingData = {
            dichvu_ids: Array.from(selectedServices),
            thoigian: document.getElementById('thoigian').value,
            ghichu: document.getElementById('ghichu').value
        };

        if (bookingData.dichvu_ids.length === 0) {
            document.getElementById('booking-message').innerHTML = 
                '<div class="alert alert-danger">Vui lòng chọn ít nhất một dịch vụ</div>';
            return;
        }

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
                selectedServices.clear();
                document.querySelectorAll('.service-card').forEach(card => {
                    card.classList.remove('selected');
                });
                updateSelectedServicesTags();
            } else {
                msgDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            }
        });
    });
});
</script>
</body>
</html> 