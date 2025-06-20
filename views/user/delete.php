<style>
.user-del-box {
    max-width: 400px;
    margin: 60px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
    text-align: center;
}
.user-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.user-del-msg {
    margin-bottom: 16px;
    font-size: 1rem;
}
.user-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    margin: 0 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.user-btn:hover {
    background: #e73370;
}
.user-btn.user-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
}
.user-btn.user-back:hover {
    background: #ffe4ec;
}
.user-msg.success { color: #43a047; }
.user-msg.error { color: #e53935; }
</style>
<div class="user-del-box">
    <div class="user-del-title">Xác nhận xoá người dùng</div>
    <div class="user-del-msg">Bạn có chắc chắn muốn xoá người dùng này không?</div>
    <input type="hidden" name="manguoidung">
    <div class="user-msg" id="user-del-msg"></div>
    <button class="user-btn user-confirm">Xoá</button>
    <button class="user-btn user-back">Quay lại</button>
</div>
<script>
document.querySelector('.user-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const userDelMsg = document.getElementById('user-del-msg');
const manguoidungInput = document.querySelector('input[name="manguoidung"]');
document.querySelector('.user-confirm').onclick = function() {
    userDelMsg.textContent = 'Đang xử lý...';
    userDelMsg.className = 'user-msg';
    fetch('http://localhost:86/cnpm-BE/api/user/deleteUser', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ manguoidung: manguoidungInput.value })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success' || data.message) {
            userDelMsg.textContent = data.message || 'Xoá người dùng thành công!';
            userDelMsg.className = 'user-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            userDelMsg.textContent = data.message || 'Xoá thất bại!';
            userDelMsg.className = 'user-msg error';
        }
    })
    .catch(() => {
        userDelMsg.textContent = 'Lỗi kết nối máy chủ!';
        userDelMsg.className = 'user-msg error';
    });
};
</script> 