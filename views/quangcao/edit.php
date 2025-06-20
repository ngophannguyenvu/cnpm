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
<script>
document.querySelector('.qc-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const qcEditForm = document.getElementById('qc-edit-form');
const qcEditMsg = document.getElementById('qc-edit-msg');
if (typeof _quangCaoData !== 'undefined' && qcEditForm.maqc.value) {
    const qc = (_quangCaoData || []).find(x => x.Maquangcao == qcEditForm.maqc.value || x.MaQC == qcEditForm.maqc.value);
    if (qc) {
        qcEditForm.tenqc.value = qc.Tieude || qc.TenQC || '';
        qcEditForm.noidung.value = qc.Noidung || '';
        qcEditForm.loaiquangcao.value = qc.Loaiquangcao || '';
        qcEditForm.hinhanh.value = qc.Image || qc.HinhAnh || '';
        qcEditForm.ngaybd.value = qc.Ngaybatdau || qc.NgayBatDau || '';
        qcEditForm.ngaykt.value = qc.Ngayketthuc || qc.NgayKetThuc || '';
        qcEditForm.manguoidung.value = qc.Manguoidung || '';
    }
}
qcEditForm.onsubmit = function(e) {
    e.preventDefault();
    qcEditMsg.textContent = 'Đang xử lý...';
    qcEditMsg.className = 'qc-msg';
    fetch('http://localhost:86/cnpm-BE/api/quangcao/' + qcEditForm.maqc.value, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            Tieude: qcEditForm.tenqc.value,
            Noidung: qcEditForm.noidung.value,
            Loaiquangcao: qcEditForm.loaiquangcao.value,
            Image: qcEditForm.hinhanh.value,
            Ngaybatdau: qcEditForm.ngaybd.value,
            Ngayketthuc: qcEditForm.ngaykt.value,
            Manguoidung: qcEditForm.manguoidung.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message || data.success || data.status === 'success') {
            qcEditMsg.textContent = data.message || 'Cập nhật quảng cáo thành công!';
            qcEditMsg.className = 'qc-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            qcEditMsg.textContent = data.message || 'Cập nhật thất bại!';
            qcEditMsg.className = 'qc-msg error';
        }
    })
    .catch(() => {
        qcEditMsg.textContent = 'Lỗi kết nối máy chủ!';
        qcEditMsg.className = 'qc-msg error';
    });
};
</script> 