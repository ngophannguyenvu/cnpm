<style>
.ttp-detail-box {
    max-width: 400px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.ttp-detail-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.ttp-detail-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 18px;
}
.ttp-detail-table th, .ttp-detail-table td {
    padding: 10px 12px;
    text-align: left;
}
.ttp-detail-table th {
    background: #ff80ab;
    color: #fff;
    width: 140px;
    font-weight: 600;
}
.ttp-detail-table tr:nth-child(even) {
    background: #ffe4ec;
}
.ttp-btn {
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
.ttp-btn:hover {
    background: #e73370;
}
.ttp-btn.ttp-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.ttp-btn.ttp-back:hover {
    background: #ffe4ec;
}
@media (max-width: 600px) {
    .ttp-detail-box { padding: 10px; }
    .ttp-detail-title { font-size: 1.1rem; }
    .ttp-detail-table th, .ttp-detail-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="ttp-detail-box">
    <div class="ttp-detail-title">Chi tiết trạng thái phòng</div>
    <table class="ttp-detail-table">
        <tr><th>Mã trạng thái phòng</th><td id="ttp-mattp"></td></tr>
        <tr><th>Tên trạng thái phòng</th><td id="ttp-tenttp"></td></tr>
    </table>
    <button class="ttp-btn ttp-back">Quay lại</button>
</div>
<script>
document.querySelector('.ttp-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
if (typeof _ttpData !== 'undefined') {
    const mattp = document.querySelector('[name="mattp"]') ? document.querySelector('[name="mattp"]').value : (typeof ttp_mattp !== 'undefined' ? ttp_mattp : '');
    const ttp = (_ttpData || []).find(x => x.MaTrangThaiPhong == mattp);
    if (ttp) {
        document.getElementById('ttp-mattp').textContent = ttp.MaTrangThaiPhong || '';
        document.getElementById('ttp-tenttp').textContent = ttp.TenTrangThaiPhong || '';
    }
}
</script> 