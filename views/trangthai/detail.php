<style>
.tt-detail-box {
    max-width: 400px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.tt-detail-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.tt-detail-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 18px;
}
.tt-detail-table th, .tt-detail-table td {
    padding: 10px 12px;
    text-align: left;
}
.tt-detail-table th {
    background: #ff80ab;
    color: #fff;
    width: 140px;
    font-weight: 600;
}
.tt-detail-table tr:nth-child(even) {
    background: #ffe4ec;
}
.tt-btn {
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
.tt-btn:hover {
    background: #e73370;
}
.tt-btn.tt-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.tt-btn.tt-back:hover {
    background: #ffe4ec;
}
@media (max-width: 600px) {
    .tt-detail-box { padding: 10px; }
    .tt-detail-title { font-size: 1.1rem; }
    .tt-detail-table th, .tt-detail-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="tt-detail-box">
    <div class="tt-detail-title">Chi tiết trạng thái</div>
    <table class="tt-detail-table">
        <tr><th>Mã trạng thái</th><td id="tt-matt"></td></tr>
        <tr><th>Tên trạng thái</th><td id="tt-tentt"></td></tr>
    </table>
    <button class="tt-btn tt-back">Quay lại</button>
</div>
<script>
document.querySelector('.tt-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
if (typeof _ttData !== 'undefined') {
    const matt = document.querySelector('[name="matt"]') ? document.querySelector('[name="matt"]').value : (typeof tt_matt !== 'undefined' ? tt_matt : '');
    const tt = (_ttData || []).find(x => x.MaTrangThai == matt);
    if (tt) {
        document.getElementById('tt-matt').textContent = tt.MaTrangThai || '';
        document.getElementById('tt-tentt').textContent = tt.TenTrangThai || '';
    }
}
</script> 