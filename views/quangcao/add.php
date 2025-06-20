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
.qc-form input, .qc-form select, .qc-form textarea {
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
<form class="qc-form" id="qc-add-form">
    <div class="qc-form-title">Thêm quảng cáo mới</div>
    <div class="qc-msg" id="qc-add-msg"></div>
    <label for="tenqc">Tên quảng cáo</label>
    <input type="text" id="tenqc" name="tenqc" required>
    <label for="noidung">Nội dung</label>
    <textarea id="noidung" name="noidung" required></textarea>
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
    <button type="submit" class="qc-btn">Thêm quảng cáo</button>
    <button type="button" class="qc-btn qc-back">Quay lại</button>
</form>
<script>
document.querySelector('.qc-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const qcAddForm = document.getElementById('qc-add-form');
const qcAddMsg = document.getElementById('qc-add-msg');
qcAddForm.onsubmit = function(e) {
    e.preventDefault();
    qcAddMsg.textContent = 'Đang xử lý...';
    qcAddMsg.className = 'qc-msg';
    fetch('http://localhost:86/cnpm-BE/api/quangcao', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            Tieude: qcAddForm.tenqc.value,
            Noidung: qcAddForm.noidung.value,
            Loaiquangcao: qcAddForm.loaiquangcao.value,
            Image: qcAddForm.hinhanh.value,
            Ngaybatdau: qcAddForm.ngaybd.value,
            Ngayketthuc: qcAddForm.ngaykt.value,
            Manguoidung: qcAddForm.manguoidung.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message || data.success || data.status === 'success') {
            qcAddMsg.textContent = data.message || 'Thêm quảng cáo thành công!';
            qcAddMsg.className = 'qc-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            qcAddMsg.textContent = data.message || 'Thêm quảng cáo thất bại!';
            qcAddMsg.className = 'qc-msg error';
        }
    })
    .catch(() => {
        qcAddMsg.textContent = 'Lỗi kết nối máy chủ!';
        qcAddMsg.className = 'qc-msg error';
    });
};
</script> 