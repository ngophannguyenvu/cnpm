<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Spa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }
        .register-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            max-width: 420px;
            width: 100%;
            text-align: center;
        }
        .register-container h2 {
            color: #E84393;
            margin-bottom: 25px;
            font-weight: 600;
        }
        .form-control:focus {
            border-color: #E84393;
            box-shadow: 0 0 0 0.25rem rgba(232, 67, 147, 0.25);
        }
        .btn-primary {
            background-color: #E84393;
            border-color: #E84393;
            padding: 10px 20px;
            font-weight: 500;
            width: 100%;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #d83682;
            border-color: #d83682;
            transform: translateY(-2px);
        }
        .form-label {
            color: #555;
            font-weight: 500;
        }
        #user-register-msg {
            margin-top: 15px;
            font-weight: 500;
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .extra-links { margin-top: 20px; }
        .extra-links a { color: #E84393; text-decoration: none; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Tạo tài khoản</h2>
        <form id="user-register-form">
            <div class="mb-3 text-start">
                <label for="hoten" class="form-label">Họ và Tên</label>
                <input type="text" class="form-control" id="hoten" name="hoten" required>
            </div>
            <div class="mb-3 text-start">
                <label for="sdt" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="sdt" name="sdt" required pattern="[0-9]{10,11}">
            </div>
            <div class="mb-3 text-start">
                <label for="diachi" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="diachi" name="diachi">
            </div>
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Địa chỉ Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3 text-start">
                <label for="ngaysinh" class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" id="ngaysinh" name="ngaysinh">
            </div>
            <div class="mb-3 text-start">
                <label for="gioitinh" class="form-label">Giới tính</label>
                <select class="form-control" id="gioitinh" name="gioitinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div class="mb-3 text-start">
                <label for="matkhau" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="matkhau" name="matkhau" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng ký</button>
            <div id="user-register-msg"></div>
        </form>
        <div class="extra-links">
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </div>
    </div>

<script>
const userRegisterForm = document.getElementById('user-register-form');
const userRegisterMsg = document.getElementById('user-register-msg');
userRegisterForm.onsubmit = function(e) {
    e.preventDefault();
    userRegisterMsg.textContent = 'Đang xử lý...';
    userRegisterMsg.className = '';
    fetch('/cnpm/api/user/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            hoten: userRegisterForm.hoten.value,
            sdt: userRegisterForm.sdt.value,
            diachi: userRegisterForm.diachi.value,
            email: userRegisterForm.email.value,
            ngaysinh: userRegisterForm.ngaysinh.value,
            gioitinh: userRegisterForm.gioitinh.value,
            matkhau: userRegisterForm.matkhau.value
        })
    })
    .then(res => res.json())
    .then(data => {
        userRegisterMsg.textContent = data.message || data.error;
        if (data.success) {
            userRegisterMsg.className = 'success';
            setTimeout(() => location.href = '/cnpm/views/user/login.php', 1500);
        } else {
            userRegisterMsg.className = 'error';
        }
    })
    .catch(() => {
        userRegisterMsg.textContent = 'Lỗi kết nối máy chủ!';
        userRegisterMsg.className = 'error';
    });
};
</script>
</body>
</html> 