<style>
.hd-detail-box {
    max-width: 480px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.hd-detail-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.hd-detail-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 18px;
}
.hd-detail-table th, .hd-detail-table td {
    padding: 10px 12px;
    text-align: left;
}
.hd-detail-table th {
    background: #ff80ab;
    color: #fff;
    width: 140px;
    font-weight: 600;
}
.hd-detail-table tr:nth-child(even) {
    background: #ffe4ec;
}
.hd-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
    display: block;
    margin: 0 auto;
}
.hd-btn:hover {
    background: #e73370;
}
.hd-btn.hd-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.hd-btn.hd-back:hover {
    background: #ffe4ec;
}
@media (max-width: 600px) {
    .hd-detail-box { padding: 10px; }
    .hd-detail-title { font-size: 1.1rem; }
    .hd-detail-table th, .hd-detail-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="hd-detail-box">
    <div class="hd-detail-title">Chi tiết hóa đơn</div>
    <table class="hd-detail-table">
        <tr><th>Mã hóa đơn</th><td id="hd-mahd"></td></tr>
        <tr><th>Ngày lập</th><td id="hd-ngay"></td></tr>
        <tr><th>Tổng tiền</th><td id="hd-tongtien"></td></tr>
        <tr><th>Trạng thái</th><td id="hd-trangthai"></td></tr>
    </table>
    <button class="hd-btn hd-back">Quay lại</button>
</div>
<script>
document.querySelector('.hd-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
if (typeof _hdData !== 'undefined') {
    const mahd = document.querySelector('[name="mahd"]') ? document.querySelector('[name="mahd"]').value : (typeof hd_mahd !== 'undefined' ? hd_mahd : '');
    const hd = (_hdData || []).find(x => x.MaHD == mahd);
    if (hd) {
        document.getElementById('hd-mahd').textContent = hd.MaHD || '';
        document.getElementById('hd-ngay').textContent = hd.NgayThanhToan || '';
        document.getElementById('hd-tongtien').textContent = hd.Tongtien ? hd.Tongtien.toLocaleString('vi-VN') + 'đ' : '';
        document.getElementById('hd-trangthai').textContent = hd.Matrangthai == 1 ? 'Đã thanh toán' : 'Chưa thanh toán';
    }
}
</script> 