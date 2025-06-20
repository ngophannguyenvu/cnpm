<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #fef7f7 0%, #fff0f3 100%); min-height: 100vh; }
        .card-custom { max-width: 400px; margin: 60px auto; border-radius: 20px; box-shadow: 0 10px 30px rgba(255, 112, 150, 0.1); }
        .card-header { background: linear-gradient(135deg, #ff7096 0%, #ff4d7e 100%); color: white; border-radius: 20px 20px 0 0; }
        .btn-main { background: linear-gradient(135deg, #ff7096 0%, #ff4d7e 100%); color: white; border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s; }
        .btn-main:hover { background: #ff4d7e; color: white; }
        label { font-weight: 500; color: #ff4d7e; }
        .user-login-form {
            max-width: 400px;
            margin: 60px auto;
            background: #fff0f6;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
            padding: 32px 24px 24px 24px;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .user-login-title {
            color: #ff4081;
            text-align: center;
            margin-bottom: 18px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .user-login-form label {
            color: #ff4081;
            font-weight: 500;
            margin-bottom: 4px;
            display: block;
        }
        .user-login-form input {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 16px;
            border: 1px solid #ffb6d5;
            border-radius: 6px;
            font-size: 1rem;
            background: #fff;
        }
        .user-login-btn {
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
        .user-login-btn:hover {
            background: #e73370;
        }
        .user-login-msg {
            text-align: center;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .user-login-msg.success { color: #43a047; }
        .user-login-msg.error { color: #e53935; }
        @media (max-width: 600px) {
            .user-login-form { padding: 10px; }
            .user-login-title { font-size: 1.1rem; }
        }
    </style>
</head>
<body>
<div class="card card-custom">
    <div class="card-header text-center">
        <span class="fs-5 fw-bold">Đăng nhập</span>
    </div>
    <div class="card-body">
        <form class="user-login-form" id="user-login-form">
            <div class="user-login-title">Đăng nhập hệ thống</div>
            <div class="user-login-msg" id="user-login-msg"></div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="user-login-btn">Đăng nhập</button>
        </form>
    </div>
</div>
<script>
const userLoginForm = document.getElementById('user-login-form');
const userLoginMsg = document.getElementById('user-login-msg');
userLoginForm.onsubmit = function(e) {
    e.preventDefault();
    userLoginMsg.textContent = 'Đang xử lý...';
    userLoginMsg.className = 'user-login-msg';
    fetch('http://localhost:86/cnpm-BE/api/user/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            email: userLoginForm.email.value,
            password: userLoginForm.password.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success' || data.message) {
            userLoginMsg.textContent = data.message || 'Đăng nhập thành công!';
            userLoginMsg.className = 'user-login-msg success';
            setTimeout(() => { if (typeof onLoginSuccess === 'function') onLoginSuccess(data); }, 1000);
        } else {
            userLoginMsg.textContent = data.message || 'Đăng nhập thất bại!';
            userLoginMsg.className = 'user-login-msg error';
        }
    })
    .catch(() => {
        userLoginMsg.textContent = 'Lỗi kết nối máy chủ!';
        userLoginMsg.className = 'user-login-msg error';
    });
};
</script>
</body>
</html> 