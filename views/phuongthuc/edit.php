<h3>Sửa phương thức</h3>
<form class="pt-form" id="pt-edit-form">
    <!-- Thông tin cũ -->
    <div id="pt-old-info" style="background:#ffe4ec;padding:12px 10px 10px 10px;border-radius:8px;margin-bottom:18px;display:none">
        <div style="color:#e73370;font-weight:bold;margin-bottom:6px">Thông tin cũ:</div>
        <div><b>Mã phương thức:</b> <span id="pt-old-mapt"></span></div>
        <div><b>Tên phương thức:</b> <span id="pt-old-tenpt"></span></div>
        <div><b>Mô tả:</b> <span id="pt-old-mota"></span></div>
    </div>
    <label>Mã phương thức: <input type="text" name="mapt" id="mapt" required readonly></label><br><br>
    <label>Tên phương thức: <input type="text" name="tenpt" id="tenpt" required></label><br><br>
    <label>Mô tả: <input type="text" name="mota" id="mota"></label><br><br>
    <button type="submit" id="pt-edit-save">Lưu thay đổi</button>
    <button type="button" class="pt-back">Quay lại</button>
    <div class="pt-form-msg" id="pt-edit-msg"></div>
</form> 