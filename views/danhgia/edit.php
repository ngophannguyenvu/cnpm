<style>
.dg-form-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
}
.dg-form-title {
    color: #ff4081;
    text-align: center;
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.dg-form label {
    display: block;
    margin-bottom: 10px;
    color: #d81b60;
    font-weight: 500;
}
.dg-form input[type="text"],
.dg-form input[type="number"],
.dg-form input[type="date"] {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ff80ab;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 1rem;
    background: #fff;
    transition: border 0.2s;
}
.dg-form input[type="text"]:focus,
.dg-form input[type="number"]:focus,
.dg-form input[type="date"]:focus {
    border: 1.5px solid #ff4081;
    outline: none;
}
.dg-form input[readonly] {
    background: #ffe4ec;
    color: #b71c5c;
}
.dg-form-btns {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dg-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 20px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.dg-btn:hover { background: #e73370; }
.dg-btn.dg-back { background: #ff80ab; }
.dg-form-msg {
    text-align: center;
    margin-top: 12px;
    font-weight: 500;
}
.dg-form-msg.success { color: #43a047; }
.dg-form-msg.error { color: #d32f2f; }
</style>
<div class="dg-form-wrap">
    <div class="dg-form-title">Sửa Đánh giá</div>
    <!-- Thông tin cũ -->
    <div id="dg-old-info" style="background:#ffe4ec;padding:12px 10px 10px 10px;border-radius:8px;margin-bottom:18px;display:none">
        <div style="color:#e73370;font-weight:bold;margin-bottom:6px">Thông tin cũ:</div>
        <div><b>Mã ĐG:</b> <span id="dg-old-madg"></span></div>
        <div><b>Số sao:</b> <span id="dg-old-danhgiasao"></span></div>
        <div><b>Nhận xét:</b> <span id="dg-old-nhanxet"></span></div>
        <div><b>Ngày đánh giá:</b> <span id="dg-old-ngaydanhgia"></span></div>
        <div><b>Mã người dùng:</b> <span id="dg-old-manguoidung"></span></div>
        <div><b>Mã hóa đơn:</b> <span id="dg-old-mahd"></span></div>
    </div>
    <form class="dg-form" id="dg-edit-form" autocomplete="off">
        <label>Mã ĐG:
            <input type="text" name="madg" required readonly>
        </label>
        <label>Số sao:
            <input type="number" name="danhgiasao" min="1" max="5" required placeholder="Nhập số sao">
        </label>
        <label>Nhận xét:
            <input type="text" name="nhanxet" required placeholder="Nhận xét">
        </label>
        <label>Ngày đánh giá:
            <input type="date" name="ngaydanhgia" required>
        </label>
        <label>Mã người dùng:
            <input type="text" name="manguoidung" required placeholder="Nhập mã người dùng">
        </label>
        <label>Mã hóa đơn:
            <input type="text" name="mahd" required placeholder="Nhập mã hóa đơn">
        </label>
        <div class="dg-form-btns">
            <button type="submit" class="dg-btn">Lưu thay đổi</button>
            <button type="button" class="dg-btn dg-back">Quay lại</button>
        </div>
        <div class="dg-form-msg" id="dg-edit-msg"></div>
    </form>
</div> 