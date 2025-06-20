<style>
.phong-detail-box {
    max-width: 480px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.phong-detail-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.phong-detail-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 18px;
}
.phong-detail-table th, .phong-detail-table td {
    padding: 10px 12px;
    text-align: left;
}
.phong-detail-table th {
    background: #ff80ab;
    color: #fff;
    width: 140px;
    font-weight: 600;
}
.phong-detail-table tr:nth-child(even) {
    background: #ffe4ec;
}
.phong-btn {
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
.phong-btn:hover {
    background: #e73370;
}
.phong-btn.phong-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.phong-btn.phong-back:hover {
    background: #ffe4ec;
}
@media (max-width: 600px) {
    .phong-detail-box { padding: 10px; }
    .phong-detail-title { font-size: 1.1rem; }
    .phong-detail-table th, .phong-detail-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="phong-detail-box">
    <div class="phong-detail-title">Chi tiết phòng</div>
    <table class="phong-detail-table">
        <tr><th>Mã phòng</th><td id="phong-maphong"></td></tr>
        <tr><th>Tên phòng</th><td id="phong-tenphong"></td></tr>
        <tr><th>Loại phòng</th><td id="phong-loaiphong"></td></tr>
        <tr><th>Mã trạng thái phòng</th><td id="phong-matrangthaiP"></td></tr>
    </table>
    <button class="phong-btn phong-back">Quay lại</button>
</div>
<script>
document.querySelector('.phong-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
if (typeof _phongData !== 'undefined') {
    const maphong = document.querySelector('[name="maphong"]') ? document.querySelector('[name="maphong"]').value : (typeof phong_maphong !== 'undefined' ? phong_maphong : '');
    const phong = (_phongData || []).find(x => x.Maphong == maphong);
    if (phong) {
        document.getElementById('phong-maphong').textContent = phong.Maphong || '';
        document.getElementById('phong-tenphong').textContent = phong.Tenphong || '';
        document.getElementById('phong-loaiphong').textContent = phong.Loaiphong || '';
        document.getElementById('phong-matrangthaiP').textContent = phong.MatrangthaiP || '';
    }
}
</script> 