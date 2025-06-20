<style>
.dl-detail-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
    text-align: center;
}
.dl-detail-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.dl-detail-list {
    list-style: none;
    padding: 0;
    margin-bottom: 18px;
}
.dl-detail-list li {
    background: #ffe4ec;
    margin-bottom: 10px;
    padding: 10px 0;
    border-radius: 6px;
    color: #b71c5c;
    font-size: 1.08rem;
}
.dl-btn {
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
.dl-btn:hover { background: #e73370; }
.dl-btn.dl-back { background: #ff80ab; }
</style>
<div class="dl-detail-wrap">
    <div class="dl-detail-title">Chi tiết Đặt lịch</div>
    <ul class="dl-detail-list">
        <li><b>Mã ĐL:</b> <span id="dl-madl"></span></li>
        <li><b>Mã người dùng:</b> <span id="dl-manguoidung"></span></li>
        <li><b>Thời gian đặt lịch:</b> <span id="dl-thoigiandatlich"></span></li>
        <li><b>Trạng thái:</b> <span id="dl-trangthai"></span></li>
    </ul>
    <button class="dl-btn dl-back">Quay lại</button>
</div>
<script>
// Gắn dữ liệu từ view cha (nếu có)
const madl = document.querySelector('[name="madl"]') ? document.querySelector('[name="madl"]').value : '';
document.getElementById('dl-madl').textContent = madl;
// Các trường còn lại sẽ được gán động nếu cần (hoặc có thể fetch chi tiết nếu muốn nâng cao)
</script> 