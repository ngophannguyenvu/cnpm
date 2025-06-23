<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/cnpm/';
require_once $rootPath . 'app/helpers/SessionHelper.php';
$user = SessionHelper::getUser();
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
    <title>Hồ Sơ Của Bạn - Spa & Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #E84393; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .navbar-brand { color: var(--primary-color) !important; font-weight: 700; }
        .profile-container { max-width: 800px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(232, 67, 147, 0.25); }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background-color: #d83682; border-color: #d83682; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/cnpm/customer">SPA & BEAUTY</a>
            <a href="/cnpm/customer" class="btn btn-light">Trở về</a>
        </div>
    </nav>
    <div class="profile-container">
        <h2 class="mb-4 text-center">Hồ Sơ Của Bạn</h2>
        <div id="profile-message" class="mb-3"></div>
        <form id="profile-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="hoten" class="form-label">Họ và Tên</label>
                    <input type="text" class="form-control" id="hoten" name="hoten" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="sdt" class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" id="sdt" name="sdt">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ngaysinh" class="form-label">Ngày sinh</label>
                    <input type="date" class="form-control" id="ngaysinh" name="ngaysinh">
                </div>
            </div>
            <div class="mb-3">
                <label for="diachi" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="diachi" name="diachi">
            </div>
            <div class="mb-3">
                <label for="gioitinh" class="form-label">Giới tính</label>
                <select class="form-select" id="gioitinh" name="gioitinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <hr>
            <h5 class="mt-4 mb-3">Đổi mật khẩu</h5>
            <div class="mb-3">
                <label for="new_password" class="form-label">Mật khẩu mới (bỏ trống nếu không đổi)</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>
            <div class="text-center">
                 <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
            </div>
        </form>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/cnpm/api/user/profile')
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                const user = data.data;
                document.getElementById('hoten').value = user.Hoten || '';
                document.getElementById('email').value = user.Email || '';
                document.getElementById('sdt').value = user.SDT || '';
                document.getElementById('ngaysinh').value = user.Ngaysinh ? user.Ngaysinh.split(' ')[0] : '';
                document.getElementById('diachi').value = user.DiaChi || '';
                document.getElementById('gioitinh').value = user.Gioitinh || 'Nam';
            } else {
                 document.getElementById('profile-message').innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            }
        });

    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            hoten: this.hoten.value,
            email: this.email.value,
            sdt: this.sdt.value,
            ngaysinh: this.ngaysinh.value,
            diachi: this.diachi.value,
            gioitinh: this.gioitinh.value,
            matkhau: this.new_password.value,
        };

        fetch('/cnpm/api/user/updateProfile', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            const msgDiv = document.getElementById('profile-message');
            if (data.success) {
                msgDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            } else {
                msgDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            }
        })
        .catch(err => {
            document.getElementById('profile-message').innerHTML = `<div class="alert alert-danger">Lỗi kết nối máy chủ.</div>`;
        });
    });
});
</script>
</body>
</html> 