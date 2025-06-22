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
    <!-- Thông tin cũ -->
    <div id="user-old-info" style="background:#ffe4ec;padding:12px 10px 10px 10px;border-radius:8px;margin-bottom:18px;display:none">
        <div style="color:#e73370;font-weight:bold;margin-bottom:6px">Thông tin cũ:</div>
        <div><b>Mã người dùng:</b> <span id="user-old-manguoidung"></span></div>
        <div><b>Họ tên:</b> <span id="user-old-hoten"></span></div>
        <div><b>SĐT:</b> <span id="user-old-sdt"></span></div>
        <div><b>Địa chỉ:</b> <span id="user-old-diachi"></span></div>
        <div><b>Email:</b> <span id="user-old-email"></span></div>
        <div><b>Ngày sinh:</b> <span id="user-old-ngaysinh"></span></div>
        <div><b>Giới tính:</b> <span id="user-old-gioitinh"></span></div>
    </div>
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