<style>
.user-form {
    max-width: 480px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.user-form-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.user-form label {
    color: #ff4081;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.user-form input, .user-form select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 16px;
    border: 1px solid #ffb6d5;
    border-radius: 6px;
    font-size: 1rem;
    background: #fff;
}
.user-form .user-btn {
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
.user-form .user-btn:hover {
    background: #e73370;
}
.user-form .user-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.user-form .user-back:hover {
    background: #ffe4ec;
}
.user-form .user-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.user-form .user-msg.success { color: #43a047; }
.user-form .user-msg.error { color: #e53935; }
@media (max-width: 600px) {
    .user-form { padding: 10px; }
    .user-form-title { font-size: 1.1rem; }
}
</style>
<form class="user-form" id="user-edit-form">
    <div class="user-form-title">Sửa người dùng</div>
    <div class="user-msg" id="user-edit-msg"></div>
    <input type="hidden" name="manguoidung">
    <label for="hoten">Họ tên</label>
    <input type="text" id="hoten" name="hoten" required>
    <label for="sdt">Số điện thoại</label>
    <input type="text" id="sdt" name="sdt" required>
    <label for="diachi">Địa chỉ</label>
    <input type="text" id="diachi" name="diachi">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
    <label for="ngaysinh">Ngày sinh</label>
    <input type="date" id="ngaysinh" name="ngaysinh">
    <label for="gioitinh">Giới tính</label>
    <select id="gioitinh" name="gioitinh">
        <option value="">-- Chọn giới tính --</option>
        <option value="Nam">Nam</option>
        <option value="Nữ">Nữ</option>
        <option value="Khác">Khác</option>
    </select>
    <button type="submit" class="user-btn">Lưu thay đổi</button>
    <button type="button" class="user-btn user-back">Quay lại</button>
</form>
<script>
document.querySelector('.user-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const userEditForm = document.getElementById('user-edit-form');
const userEditMsg = document.getElementById('user-edit-msg');
if (typeof _userData !== 'undefined' && userEditForm.manguoidung.value) {
    const user = (_userData || []).find(x => x.Manguoidung == userEditForm.manguoidung.value);
    if (user) {
        userEditForm.hoten.value = user.Hoten || '';
        userEditForm.sdt.value = user.SDT || '';
        userEditForm.diachi.value = user.DiaChi || '';
        userEditForm.email.value = user.Email || '';
        userEditForm.ngaysinh.value = user.Ngaysinh || '';
        userEditForm.gioitinh.value = user.Gioitinh || '';
    }
}
userEditForm.onsubmit = function(e) {
    e.preventDefault();
    userEditMsg.textContent = 'Đang xử lý...';
    userEditMsg.className = 'user-msg';
    fetch('http://localhost:86/cnpm-BE/api/user/updateUser', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            manguoidung: userEditForm.manguoidung.value,
            hoten: userEditForm.hoten.value,
            sdt: userEditForm.sdt.value,
            diachi: userEditForm.diachi.value,
            email: userEditForm.email.value,
            ngaysinh: userEditForm.ngaysinh.value,
            gioitinh: userEditForm.gioitinh.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success' || data.message) {
            userEditMsg.textContent = data.message || 'Cập nhật người dùng thành công!';
            userEditMsg.className = 'user-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            userEditMsg.textContent = data.message || 'Cập nhật thất bại!';
            userEditMsg.className = 'user-msg error';
        }
    })
    .catch(() => {
        userEditMsg.textContent = 'Lỗi kết nối máy chủ!';
        userEditMsg.className = 'user-msg error';
    });
};
</script> 