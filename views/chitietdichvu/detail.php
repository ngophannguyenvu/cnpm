<style>
.ctdv-detail-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
    text-align: center;
}
.ctdv-detail-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.ctdv-detail-list {
    list-style: none;
    padding: 0;
    margin-bottom: 18px;
}
.ctdv-detail-list li {
    background: #ffe4ec;
    margin-bottom: 10px;
    padding: 10px 0;
    border-radius: 6px;
    color: #b71c5c;
    font-size: 1.08rem;
}
.ctdv-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 20px;
    font-size: 1rem;
    cursor: pointer;
    margin: 0 8px;
    transition: background 0.2s;
}
.ctdv-btn:hover { background: #e73370; }
.ctdv-btn.ctdv-back { background: #ff80ab; }
</style>
<div class="ctdv-detail-wrap">
    <div class="ctdv-detail-title">Chi tiết Dịch vụ</div>
    <ul class="ctdv-detail-list">
        <li><b>Mã ĐL:</b> <span id="ctdv-madl"></span></li>
        <li><b>Mã DV:</b> <span id="ctdv-madv"></span></li>
    </ul>
    <button class="ctdv-btn ctdv-back">Quay lại</button>
</div>
<script>
// Gắn dữ liệu từ view cha
const madl = document.querySelector('[name="madl"]') ? document.querySelector('[name="madl"]').value : '';
const madv = document.querySelector('[name="madv"]') ? document.querySelector('[name="madv"]').value : '';
document.getElementById('ctdv-madl').textContent = madl;
document.getElementById('ctdv-madv').textContent = madv;
</script> 