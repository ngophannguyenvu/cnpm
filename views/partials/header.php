<?php
require_once 'app/helpers/SessionHelper.php';
$user = SessionHelper::getUser();
?>
<style>
    .main-header {
        background-color: #fff;
        padding: 10px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        font-family: 'Poppins', sans-serif;
    }
    .main-header .logo {
        font-size: 1.5rem;
        font-weight: 700;
        color: #E84393;
        text-decoration: none;
    }
    .main-header .nav-links {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .main-header .nav-links a,
    .main-header .nav-links button {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        background-color: transparent;
        cursor: pointer;
        font-size: 0.95rem;
    }
    .main-header .nav-links a:hover {
        background-color: #f8f9fa;
        color: #E84393;
    }
    .main-header .nav-links .btn-register {
        background-color: #E84393;
        color: #fff;
        border-color: #E84393;
    }
    .main-header .nav-links .btn-register:hover {
        background-color: #d83682;
        color: #fff;
    }
    .main-header .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .main-header .user-info span {
        font-weight: 500;
    }
    .main-header .user-info button {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
     .main-header .user-info button:hover {
        background-color: #e9ecef;
    }
</style>

<header class="main-header">
    <a href="/cnpm/" class="logo">SPA & BEAUTY</a>
    <nav class="nav-links">
        <?php if ($user): ?>
            <div class="user-info">
                <span>Xin chào, <?php echo htmlspecialchars($user['username']); ?></span>
                <button id="logout-btn">Đăng xuất</button>
            </div>
        <?php else: ?>
            <a href="/cnpm/views/user/login.php">Đăng nhập</a>
            <a href="/cnpm/views/user/register.php" class="btn-register">Đăng ký</a>
        <?php endif; ?>
    </nav>
</header>

<?php if ($user): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.onclick = function(e) {
                e.preventDefault();
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
                .catch(err => console.error('Logout failed:', err));
            };
        }
    });
</script>
<?php endif; ?> 