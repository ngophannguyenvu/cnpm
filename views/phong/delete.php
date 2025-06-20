<style>
.phong-del-box {
    max-width: 400px;
    margin: 60px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
    text-align: center;
}
.phong-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.phong-del-msg {
    margin-bottom: 16px;
    font-size: 1rem;
}
.phong-btn {
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
.phong-btn:hover {
    background: #e73370;
}
.phong-btn.phong-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
}
.phong-btn.phong-back:hover {
    background: #ffe4ec;
}
.phong-msg.success { color: #43a047; }
.phong-msg.error { color: #e53935; }
</style>
<div class="phong-del-box">
    <div class="phong-del-title">Xác nhận xoá phòng</div>
    <div class="phong-del-msg">Bạn có chắc chắn muốn xoá phòng này không?</div>
    <input type="hidden" name="maphong">
    <div class="phong-msg" id="phong-del-msg"></div>
    <button class="phong-btn phong-confirm">Xoá</button>
    <button class="phong-btn phong-back">Quay lại</button>
</div>
<script>
document.querySelector('.phong-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const phongDelMsg = document.getElementById('phong-del-msg');
const maphongInput = document.querySelector('input[name="maphong"]');
document.querySelector('.phong-confirm').onclick = function() {
    phongDelMsg.textContent = 'Đang xử lý...';
    phongDelMsg.className = 'phong-msg';
    fetch('http://localhost:86/cnpm-BE/api/phong/' + maphongInput.value, {
        method: 'DELETE'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success' || data.message) {
            phongDelMsg.textContent = data.message || 'Xoá phòng thành công!';
            phongDelMsg.className = 'phong-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            phongDelMsg.textContent = data.message || 'Xoá thất bại!';
            phongDelMsg.className = 'phong-msg error';
        }
    })
    .catch(() => {
        phongDelMsg.textContent = 'Lỗi kết nối máy chủ!';
        phongDelMsg.className = 'phong-msg error';
    });
};
</script> 