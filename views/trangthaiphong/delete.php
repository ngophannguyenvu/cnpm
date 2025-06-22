<style>
.ttp-del-box {
    max-width: 400px;
    margin: 60px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
    text-align: center;
}
.ttp-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.ttp-del-msg {
    margin-bottom: 16px;
    font-size: 1rem;
}
.ttp-btn {
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
.ttp-btn:hover {
    background: #e73370;
}
.ttp-btn.ttp-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
}
.ttp-btn.ttp-back:hover {
    background: #ffe4ec;
}
.ttp-msg.success { color: #43a047; }
.ttp-msg.error { color: #e53935; }
</style>
<div class="ttp-del-box">
    <div class="ttp-del-title">Xác nhận xoá trạng thái phòng</div>
    <div class="ttp-del-msg">Bạn có chắc chắn muốn xoá trạng thái phòng này không?</div>
    <input type="hidden" name="mattp">
    <div class="ttp-msg" id="ttp-del-msg"></div>
    <button class="ttp-btn ttp-confirm">Xoá</button>
    <button class="ttp-btn ttp-back">Quay lại</button>
</div>
<!-- Script xử lý đã được chuyển sang index.php --> 