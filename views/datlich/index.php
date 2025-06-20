<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Chi tiết Dịch vụ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 20px;
        }
        .ctdv-container {
            max-width: 960px;
            margin: auto;
            background: #ffe6f0;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(255, 105, 180, 0.2);
        }
        .ctdv-title {
            font-size: 26px;
            color: #d63384;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        th, td {
            border: 1px solid #f3c3d9;
            padding: 12px;
            text-align: center;
        }
        thead {
            background-color: #f8d7da;
            color: #721c24;
        }
        .ctdv-btn {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .ctdv-add {
            background-color: #ff69b4;
            color: white;
        }
        .ctdv-detail { background-color: #ffb6c1; }
        .ctdv-edit   { background-color: #f08080; }
        .ctdv-delete { background-color: #dc143c; color: white; }

        .ctdv-empty {
            text-align: center;
            color: #888;
            font-style: italic;
        }
        .dl-container {
            max-width: 950px;
            margin: 40px auto;
            background: #fff0f6;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(255, 105, 135, 0.13);
            padding: 32px 24px 24px 24px;
        }
        .dl-title {
            color: #ff4081;
            text-align: center;
            margin-bottom: 18px;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .dl-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        .dl-table th, .dl-table td {
            padding: 12px 16px;
            text-align: center;
        }
        .dl-table th {
            background: #ff80ab;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .dl-table tr:nth-child(even) {
            background: #ffe4ec;
        }
        .dl-table tr:hover {
            background: #ffd1e6;
        }
        .dl-btn {
            background: #ff4081;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            margin: 0 2px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .dl-btn:hover {
            background: #e73370;
        }
        .dl-btn.dl-add {
            background: linear-gradient(90deg, #ff80ab, #ff4081);
            font-weight: bold;
            margin-top: 8px;
            width: 160px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        @media (max-width: 600px) {
            .dl-container { padding: 10px; }
            .dl-title { font-size: 1.2rem; }
            .dl-table th, .dl-table td { padding: 6px 4px; font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <div class="dl-container">
        <div class="dl-title"><i class="fa-solid fa-calendar-check me-2"></i>Quản lý Đặt lịch</div>
        <div id="dl-table-wrap">
            <table class="dl-table">
                <thead>
                    <tr>
                        <th>Mã ĐL</th>
                        <th>Mã người dùng</th>
                        <th>Thời gian đặt</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="dl-tbody">
                    <tr><td colspan="5">Đang tải dữ liệu...</td></tr>
                </tbody>
            </table>
        </div>
        <button class="dl-btn dl-add" id="dl-btn-add"><i class="fa fa-plus me-2"></i>Thêm mới</button>
        <div id="dl-content"></div>
    </div>
    <script>
function loadDatLich() {
    const tbody = document.getElementById('dl-list');
    const msg = document.getElementById('dl-msg');
    console.log('Starting loadDatLich...'); // Debug bước 1

    if (!tbody) {
        if (msg) {
            msg.textContent = 'Không tìm thấy bảng dữ liệu!';
            msg.classList.add('error');
        }
        console.error('Element dl-list not found!');
        return;
    }

    // Xóa nội dung cũ
    tbody.innerHTML = '<tr><td colspan="5">Đang tải dữ liệu...</td></tr>';
    console.log('Cleared tbody, starting fetch...'); // Debug bước 2

    fetch('http://localhost:86/cnpm-BE/api/datlich')
        .then(res => {
            console.log('Fetch response status:', res.status); // Debug bước 3
            if (!res.ok) {
                throw new Error(`HTTP error! Status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Fetched Data:', data); // Debug bước 4
            tbody.innerHTML = '';
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.MaDL || ''}</td>
                        <td>${item.Manguoidung || ''}</td>
                        <td>${item.Thoigiandatlich || ''}</td>
                        <td>${item.Trangthai_ || ''}</td>
                        <td>
                            <a href="edit.php?madl=${encodeURIComponent(item.MaDL || '')}">Sửa</a>
                            <a href="delete.php?madl=${encodeURIComponent(item.MaDL || '')}">Xóa</a>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu đặt lịch!</td></tr>';
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error); // Debug bước 5
            tbody.innerHTML = '<tr><td colspan="5">Lỗi tải dữ liệu!</td></tr>';
            if (msg) {
                msg.textContent = error.message.includes('Failed to fetch') 
                    ? 'Lỗi kết nối máy chủ hoặc vấn đề CORS!' 
                    : error.message;
                msg.classList.add('error');
            }
        });
}

document.addEventListener('DOMContentLoaded', loadDatLich);
</script>
</body>
</html>