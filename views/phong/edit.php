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
    <!-- Thông tin cũ -->
    <div id="phong-old-info" style="background:#ffe4ec;padding:12px 10px 10px 10px;border-radius:8px;margin-bottom:18px;display:none">
        <div style="color:#e73370;font-weight:bold;margin-bottom:6px">Thông tin cũ:</div>
        <div><b>Mã phòng:</b> <span id="phong-old-maphong"></span></div>
        <div><b>Tên phòng:</b> <span id="phong-old-tenphong"></span></div>
        <div><b>Loại phòng:</b> <span id="phong-old-loaiphong"></span></div>
        <div><b>Mã trạng thái phòng:</b> <span id="phong-old-matrangthaiP"></span></div>
    </div>
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