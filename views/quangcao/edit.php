<style>
.qc-form {
    max-width: 480px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.qc-form-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.qc-form label {
    color: #ff4081;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.qc-form input, .qc-form select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 16px;
    border: 1px solid #ffb6d5;
    border-radius: 6px;
    font-size: 1rem;
    background: #fff;
}
.qc-form .qc-btn {
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
.qc-form .qc-btn:hover {
    background: #e73370;
}
.qc-form .qc-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.qc-form .qc-back:hover {
    background: #ffe4ec;
}
.qc-form .qc-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.qc-form .qc-msg.success { color: #43a047; }
.qc-form .qc-msg.error { color: #e53935; }
@media (max-width: 600px) {
    .qc-form { padding: 10px; }
    .qc-form-title { font-size: 1.1rem; }
}
</style>
<form class="qc-form" id="qc-edit-form">
    <div class="qc-form-title">Sửa quảng cáo</div>
    <div class="qc-msg" id="qc-edit-msg"></div>
    <!-- Thông tin cũ -->
    <div id="qc-old-info" style="background:#ffe4ec;padding:12px 10px 10px 10px;border-radius:8px;margin-bottom:18px;display:none">
        <div style="color:#e73370;font-weight:bold;margin-bottom:6px">Thông tin cũ:</div>
        <div><b>Mã quảng cáo:</b> <span id="qc-old-maqc"></span></div>
        <div><b>Tên quảng cáo:</b> <span id="qc-old-tenqc"></span></div>
        <div><b>Nội dung:</b> <span id="qc-old-noidung"></span></div>
        <div><b>Loại quảng cáo:</b> <span id="qc-old-loaiquangcao"></span></div>
        <div><b>Hình ảnh:</b> <span id="qc-old-hinhanh"></span></div>
        <div><b>Ngày bắt đầu:</b> <span id="qc-old-ngaybd"></span></div>
        <div><b>Ngày kết thúc:</b> <span id="qc-old-ngaykt"></span></div>
        <div><b>Mã người dùng:</b> <span id="qc-old-manguoidung"></span></div>
    </div>
    <input type="hidden" name="maqc">
    <label for="tenqc">Tên quảng cáo</label>
    <input type="text" id="tenqc" name="tenqc" required>
    <label for="noidung">Nội dung</label>
    <textarea id="noidung" name="noidung" required style="width:100%;padding:8px 10px;margin-bottom:16px;border:1px solid #ffb6d5;border-radius:6px;font-size:1rem;"></textarea>
    <label for="loaiquangcao">Loại quảng cáo</label>
    <input type="text" id="loaiquangcao" name="loaiquangcao" required>
    <label for="hinhanh">Hình ảnh (URL)</label>
    <input type="text" id="hinhanh" name="hinhanh" required>
    <label for="ngaybd">Ngày bắt đầu</label>
    <input type="date" id="ngaybd" name="ngaybd" required>
    <label for="ngaykt">Ngày kết thúc</label>
    <input type="date" id="ngaykt" name="ngaykt" required>
    <label for="manguoidung">Mã người dùng</label>
    <input type="text" id="manguoidung" name="manguoidung" required>
    <button type="submit" class="qc-btn">Lưu thay đổi</button>
    <button type="button" class="qc-btn qc-back">Quay lại</button>
</form> 