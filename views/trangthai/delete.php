<style>
.tt-del-box {
    max-width: 400px;
    margin: 60px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
    text-align: center;
}
.tt-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.tt-del-msg {
    margin-bottom: 16px;
    font-size: 1rem;
}
.tt-btn {
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
.tt-btn:hover {
    background: #e73370;
}
.tt-btn.tt-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
}
.tt-btn.tt-back:hover {
    background: #ffe4ec;
}
.tt-msg.success { color: #43a047; }
.tt-msg.error { color: #e53935; }
</style>
<div class="tt-del-box">
    <div class="tt-del-title">Xác nhận xoá trạng thái</div>
    <div class="tt-del-msg">Bạn có chắc chắn muốn xoá trạng thái này không?</div>
    <input type="hidden" name="matt">
    <div class="tt-msg" id="tt-del-msg"></div>
    <button class="tt-btn tt-confirm">Xoá</button>
    <button class="tt-btn tt-back">Quay lại</button>
</div>
<!-- Script xử lý đã được chuyển sang index.php --> 