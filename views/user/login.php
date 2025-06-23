<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Spa</title>
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
        }
        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            max-width: 420px;
            width: 100%;
            text-align: center;
        }
        .login-container h2 {
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
        #user-login-msg {
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
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <form id="user-login-form">
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Địa chỉ Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3 text-start">
                <label for="matkhau" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="matkhau" name="matkhau" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
            <div id="user-login-msg"></div>
        </form>
        <div class="extra-links">
            <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
        </div>
    </div>

<script>
const userLoginForm = document.getElementById('user-login-form');
const userLoginMsg = document.getElementById('user-login-msg');
userLoginForm.onsubmit = function(e) {
    e.preventDefault();
    userLoginMsg.textContent = 'Đang xử lý...';
    userLoginMsg.className = '';
    fetch('/cnpm/api/user/login', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            email: userLoginForm.email.value,
            matkhau: userLoginForm.matkhau.value
        })
    })
    .then(res => res.json())
    .then(data => {
        userLoginMsg.textContent = data.message || data.error;
        if (data.success) {
            userLoginMsg.className = 'success';
            setTimeout(() => {
                if (data.role === 'admin') {
                    location.href = '/cnpm/';
                } else {
                    location.href = '/cnpm/customer';
                }
            }, 1500);
        } else {
            userLoginMsg.className = 'error';
        }
    })
    .catch(() => {
        userLoginMsg.textContent = 'Lỗi kết nối máy chủ!';
        userLoginMsg.className = 'error';
    });
};
</script>
</body>
</html> 