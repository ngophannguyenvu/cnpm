<style>
.phong-form {
    max-width: 480px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.phong-form-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.phong-form label {
    color: #ff4081;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.phong-form input, .phong-form select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 16px;
    border: 1px solid #ffb6d5;
    border-radius: 6px;
    font-size: 1rem;
    background: #fff;
}
.phong-form .phong-btn {
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
.phong-form .phong-btn:hover {
    background: #e73370;
}
.phong-form .phong-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.phong-form .phong-back:hover {
    background: #ffe4ec;
}
.phong-form .phong-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.phong-form .phong-msg.success { color: #43a047; }
.phong-form .phong-msg.error { color: #e53935; }
@media (max-width: 600px) {
    .phong-form { padding: 10px; }
    .phong-form-title { font-size: 1.1rem; }
}
</style>
<form class="phong-form" id="phong-add-form">
    <div class="phong-form-title">Thêm phòng mới</div>
    <div class="phong-msg" id="phong-add-msg"></div>
    <label for="tenphong">Tên phòng</label>
    <input type="text" id="tenphong" name="tenphong" required>
    <label for="loaiphong">Loại phòng</label>
    <input type="text" id="loaiphong" name="loaiphong" required>
    <label for="matrangthaiP">Mã trạng thái phòng</label>
    <input type="text" id="matrangthaiP" name="matrangthaiP" required>
    <button type="submit" class="phong-btn">Thêm phòng</button>
    <button type="button" class="phong-btn phong-back">Quay lại</button>
</form>
<script>
document.querySelector('.phong-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const phongAddForm = document.getElementById('phong-add-form');
const phongAddMsg = document.getElementById('phong-add-msg');
phongAddForm.onsubmit = function(e) {
    e.preventDefault();
    phongAddMsg.textContent = 'Đang xử lý...';
    phongAddMsg.className = 'phong-msg';
    fetch('http://localhost:86/cnpm-BE/api/phong', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            Tenphong: phongAddForm.tenphong.value,
            Loaiphong: phongAddForm.loaiphong.value,
            MatrangthaiP: phongAddForm.matrangthaiP.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success' || data.message) {
            phongAddMsg.textContent = data.message || 'Thêm phòng thành công!';
            phongAddMsg.className = 'phong-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            phongAddMsg.textContent = data.message || 'Thêm phòng thất bại!';
            phongAddMsg.className = 'phong-msg error';
        }
    })
    .catch(() => {
        phongAddMsg.textContent = 'Lỗi kết nối máy chủ!';
        phongAddMsg.className = 'phong-msg error';
    });
};
</script> 