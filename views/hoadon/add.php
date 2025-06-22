<style>
.hd-form {
    max-width: 480px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.hd-form-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.hd-form label {
    color: #ff4081;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.hd-form input, .hd-form select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 16px;
    border: 1px solid #ffb6d5;
    border-radius: 6px;
    font-size: 1rem;
    background: #fff;
}
.hd-form .hd-btn {
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
.hd-form .hd-btn:hover {
    background: #e73370;
}
.hd-form .hd-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.hd-form .hd-back:hover {
    background: #ffe4ec;
}
.hd-form .hd-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.hd-form .hd-msg.success { color: #43a047; }
.hd-form .hd-msg.error { color: #e53935; }
@media (max-width: 600px) {
    .hd-form { padding: 10px; }
    .hd-form-title { font-size: 1.1rem; }
}
</style>
<form class="hd-form" id="hd-add-form">
    <div class="hd-form-title">Thêm hóa đơn mới</div>
    <div class="hd-msg" id="hd-add-msg"></div>
    <label for="ngay">Ngày lập</label>
    <input type="date" id="ngay" name="ngay" required>
    <label for="tongtien">Tổng tiền</label>
    <input type="number" id="tongtien" name="tongtien" min="0" required>
    <label for="trangthai">Trạng thái</label>
    <select id="trangthai" name="trangthai" required>
        <option value="1">Đã thanh toán</option>
    </select>
    <label for="madl">Mã đại lý</label>
    <input type="text" id="madl" name="madl" required>
    <label for="manguoidung">Mã người dùng</label>
    <input type="text" id="manguoidung" name="manguoidung" required>
    <label for="maphong">Mã phòng</label>
    <input type="text" id="maphong" name="maphong" required>
    <label for="mapt">Mã phương thức</label>
    <input type="text" id="mapt" name="mapt" required>
    <button type="submit" class="hd-btn">Thêm hóa đơn</button>
    <button type="button" class="hd-btn hd-back">Quay lại</button>
</form> 