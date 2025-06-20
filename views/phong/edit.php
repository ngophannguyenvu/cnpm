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
<form class="phong-form" id="phong-edit-form">
    <div class="phong-form-title">Sửa phòng</div>
    <div class="phong-msg" id="phong-edit-msg"></div>
    <input type="hidden" name="maphong">
    <label for="tenphong">Tên phòng</label>
    <input type="text" id="tenphong" name="tenphong" required>
    <label for="loaiphong">Loại phòng</label>
    <input type="text" id="loaiphong" name="loaiphong" required>
    <label for="matrangthaiP">Mã trạng thái phòng</label>
    <input type="text" id="matrangthaiP" name="matrangthaiP" required>
    <button type="submit" class="phong-btn">Lưu thay đổi</button>
    <button type="button" class="phong-btn phong-back">Quay lại</button>
</form>
<script>
document.querySelector('.phong-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const phongEditForm = document.getElementById('phong-edit-form');
const phongEditMsg = document.getElementById('phong-edit-msg');
if (typeof _phongData !== 'undefined' && phongEditForm.maphong.value) {
    const phong = (_phongData || []).find(x => x.Maphong == phongEditForm.maphong.value);
    if (phong) {
        phongEditForm.tenphong.value = phong.Tenphong || '';
        phongEditForm.loaiphong.value = phong.Loaiphong || '';
        phongEditForm.matrangthaiP.value = phong.MatrangthaiP || '';
    }
}
phongEditForm.onsubmit = function(e) {
    e.preventDefault();
    phongEditMsg.textContent = 'Đang xử lý...';
    phongEditMsg.className = 'phong-msg';
    fetch('http://localhost:86/cnpm-BE/api/phong/' + phongEditForm.maphong.value, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            Tenphong: phongEditForm.tenphong.value,
            Loaiphong: phongEditForm.loaiphong.value,
            MatrangthaiP: phongEditForm.matrangthaiP.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success' || data.message) {
            phongEditMsg.textContent = data.message || 'Cập nhật phòng thành công!';
            phongEditMsg.className = 'phong-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            phongEditMsg.textContent = data.message || 'Cập nhật thất bại!';
            phongEditMsg.className = 'phong-msg error';
        }
    })
    .catch(() => {
        phongEditMsg.textContent = 'Lỗi kết nối máy chủ!';
        phongEditMsg.className = 'phong-msg error';
    });
};
</script> 