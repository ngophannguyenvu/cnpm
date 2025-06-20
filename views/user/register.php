<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #fef7f7 0%, #fff0f3 100%); min-height: 100vh; }
        .card-custom { max-width: 500px; margin: 40px auto; border-radius: 20px; box-shadow: 0 10px 30px rgba(255, 112, 150, 0.1); }
        .card-header { background: linear-gradient(135deg, #ff7096 0%, #ff4d7e 100%); color: white; border-radius: 20px 20px 0 0; }
        .btn-main { background: linear-gradient(135deg, #ff7096 0%, #ff4d7e 100%); color: white; border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s; }
        .btn-main:hover { background: #ff4d7e; color: white; }
        label { font-weight: 500; color: #ff4d7e; }
        .user-register-form {
            max-width: 400px;
            margin: 60px auto;
            background: #fff0f6;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
            padding: 32px 24px 24px 24px;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .user-register-title {
            color: #ff4081;
            text-align: center;
            margin-bottom: 18px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .user-register-form label {
            color: #ff4081;
            font-weight: 500;
            margin-bottom: 4px;
            display: block;
        }
        .user-register-form input {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 16px;
            border: 1px solid #ffb6d5;
            border-radius: 6px;
            font-size: 1rem;
            background: #fff;
        }
        .user-register-btn {
            width: 100%;
            background: linear-gradient(90deg, #ff80ab, #ff4081);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 0;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 8px;
            transition: background 0.2s;
        }
        .user-register-btn:hover {
            background: #e73370;
        }
        .user-register-msg {
            text-align: center;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .user-register-msg.success { color: #43a047; }
        .user-register-msg.error { color: #e53935; }
        @media (max-width: 600px) {
            .user-register-form { padding: 10px; }
            .user-register-title { font-size: 1.1rem; }
        }
    </style>
</head>
<body>
<div class="card card-custom">
    <div class="card-header text-center">
        <span class="fs-5 fw-bold">Đăng ký tài khoản</span>
    </div>
    <div class="card-body">
        <form class="user-register-form" id="user-register-form">
            <div class="user-register-title">Đăng ký tài khoản</div>
            <div class="user-register-msg" id="user-register-msg"></div>
            <label for="hoten">Họ tên</label>
            <input type="text" id="hoten" name="hoten" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="user-register-btn">Đăng ký</button>
        </form>
    </div>
</div>
<script>
const userRegisterForm = document.getElementById('user-register-form');
const userRegisterMsg = document.getElementById('user-register-msg');
userRegisterForm.onsubmit = function(e) {
    e.preventDefault();
    userRegisterMsg.textContent = 'Đang xử lý...';
    userRegisterMsg.className = 'user-register-msg';
    fetch('http://localhost:86/cnpm-BE/api/user/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            hoten: userRegisterForm.hoten.value,
            email: userRegisterForm.email.value,
            password: userRegisterForm.password.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success' || data.message) {
            userRegisterMsg.textContent = data.message || 'Đăng ký thành công!';
            userRegisterMsg.className = 'user-register-msg success';
            setTimeout(() => { if (typeof onRegisterSuccess === 'function') onRegisterSuccess(data); }, 1000);
        } else {
            userRegisterMsg.textContent = data.message || 'Đăng ký thất bại!';
            userRegisterMsg.className = 'user-register-msg error';
        }
    })
    .catch(() => {
        userRegisterMsg.textContent = 'Lỗi kết nối máy chủ!';
        userRegisterMsg.className = 'user-register-msg error';
    });
};
</script>
</body>
</html> 