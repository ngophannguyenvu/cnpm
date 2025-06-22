<style>
.dv-form { max-width: 400px; margin: 40px auto; background: #fff0f6; border-radius: 16px; box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15); padding: 32px 24px 24px 24px; font-family: 'Segoe UI', Arial, sans-serif; }
.dv-form-title { color: #ff4081; text-align: center; margin-bottom: 18px; font-size: 1.5rem; font-weight: bold; }
.dv-form label { color: #ff4081; font-weight: 500; margin-bottom: 4px; display: block; }
.dv-form input, .dv-form textarea { width: 100%; padding: 8px 10px; margin-bottom: 16px; border: 1px solid #ffb6d5; border-radius: 6px; font-size: 1rem; background: #fff; }
.dv-form .dv-btn { width: 100%; background: linear-gradient(90deg, #ff80ab, #ff4081); color: #fff; border: none; border-radius: 6px; padding: 10px 0; font-size: 1.1rem; font-weight: bold; cursor: pointer; margin-bottom: 8px; transition: background 0.2s; }
.dv-form .dv-btn:hover { background: #e73370; }
.dv-form .dv-back { background: #fff; color: #ff4081; border: 1px solid #ff80ab; margin-top: 8px; }
.dv-form .dv-back:hover { background: #ffe4ec; }
.dv-form .dv-msg { text-align: center; margin-bottom: 10px; font-size: 1rem; }
.dv-form .dv-msg.success { color: #43a047; }
.dv-form .dv-msg.error { color: #e53935; }
@media (max-width: 600px) { .dv-form { padding: 10px; } .dv-form-title { font-size: 1.1rem; } }
</style>
<form class="dv-form" id="dv-edit-form">
    <div class="dv-form-title">Sửa dịch vụ</div>
    <div class="dv-msg" id="dv-edit-msg"></div>
    <div id="dv-old-info" style="background:#ffe4ec;padding:12px 10px 10px 10px;border-radius:8px;margin-bottom:18px;display:none">
        <div style="color:#e73370;font-weight:bold;margin-bottom:6px">Thông tin cũ:</div>
        <div><b>Mã DV:</b> <span id="dv-old-madv"></span></div>
        <div><b>Tên dịch vụ:</b> <span id="dv-old-tendichvu"></span></div>
        <div><b>Giá:</b> <span id="dv-old-gia"></span></div>
        <div><b>Mô tả:</b> <span id="dv-old-mota"></span></div>
    </div>
    <input type="hidden" name="madv">
    <label for="tendichvu">Tên dịch vụ</label>
    <input type="text" id="tendichvu" name="tendichvu" required>
    <label for="gia">Giá</label>
    <input type="number" id="gia" name="gia" min="0" required>
    <label for="mota">Mô tả</label>
    <textarea id="mota" name="mota" required></textarea>
    <button type="submit" class="dv-btn">Lưu thay đổi</button>
    <button type="button" class="dv-btn dv-back">Quay lại</button>
</form> 