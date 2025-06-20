<style>
.qc-detail-box {
    max-width: 480px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.qc-detail-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.qc-detail-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 18px;
}
.qc-detail-table th, .qc-detail-table td {
    padding: 10px 12px;
    text-align: left;
}
.qc-detail-table th {
    background: #ff80ab;
    color: #fff;
    width: 140px;
    font-weight: 600;
}
.qc-detail-table tr:nth-child(even) {
    background: #ffe4ec;
}
.qc-btn {
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
.qc-btn:hover {
    background: #e73370;
}
.qc-btn.qc-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.qc-btn.qc-back:hover {
    background: #ffe4ec;
}
@media (max-width: 600px) {
    .qc-detail-box { padding: 10px; }
    .qc-detail-title { font-size: 1.1rem; }
    .qc-detail-table th, .qc-detail-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="qc-detail-box">
    <div class="qc-detail-title">Chi tiết quảng cáo</div>
    <table class="qc-detail-table">
        <tr><th>Mã QC</th><td id="qc-maqc"></td></tr>
        <tr><th>Tên QC</th><td id="qc-tenqc"></td></tr>
        <tr><th>Hình ảnh</th><td id="qc-hinhanh"></td></tr>
        <tr><th>Ngày bắt đầu</th><td id="qc-ngaybd"></td></tr>
        <tr><th>Ngày kết thúc</th><td id="qc-ngaykt"></td></tr>
    </table>
    <button class="qc-btn qc-back">Quay lại</button>
</div>
<script>
document.querySelector('.qc-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
if (typeof _quangCaoData !== 'undefined') {
    const maqc = document.querySelector('[name="maqc"]') ? document.querySelector('[name="maqc"]').value : (typeof qc_maqc !== 'undefined' ? qc_maqc : '');
    const detailBox = document.querySelector('.qc-detail-box');
    const loadingDiv = document.createElement('div');
    loadingDiv.textContent = 'Đang tải dữ liệu...';
    loadingDiv.style.textAlign = 'center';
    detailBox.appendChild(loadingDiv);
    fetch('http://localhost:86/cnpm-BE/api/quangcao/' + maqc)
        .then(res => res.json())
        .then(qc => {
            loadingDiv.remove();
            if (qc) {
                document.getElementById('qc-maqc').textContent = qc.Maquangcao || qc.MaQC || '';
                document.getElementById('qc-tenqc').textContent = qc.Tieude || qc.TenQC || '';
                document.getElementById('qc-hinhanh').innerHTML = qc.Image ? `<img src='${qc.Image}' style='max-width:120px;max-height:80px;border-radius:8px;'>` : '';
                document.getElementById('qc-ngaybd').textContent = qc.Ngaybatdau || qc.NgayBatDau || '';
                document.getElementById('qc-ngaykt').textContent = qc.Ngayketthuc || qc.NgayKetThuc || '';
            }
        });
}
</script> 