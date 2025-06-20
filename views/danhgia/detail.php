<style>
.dg-detail-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
    text-align: center;
}
.dg-detail-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.dg-detail-list {
    list-style: none;
    padding: 0;
    margin-bottom: 18px;
}
.dg-detail-list li {
    background: #ffe4ec;
    margin-bottom: 10px;
    padding: 10px 0;
    border-radius: 6px;
    color: #b71c5c;
    font-size: 1.08rem;
}
.dg-btn {
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
.dg-btn:hover { background: #e73370; }
.dg-btn.dg-back { background: #ff80ab; }
</style>
<div class="dg-detail-wrap">
    <div class="dg-detail-title">Chi tiết Đánh giá</div>
    <ul class="dg-detail-list">
        <li><b>Mã ĐG:</b> <span id="dg-madg"></span></li>
        <li><b>Số sao:</b> <span id="dg-danhgiasao"></span></li>
        <li><b>Nhận xét:</b> <span id="dg-nhanxet"></span></li>
        <li><b>Ngày đánh giá:</b> <span id="dg-ngaydanhgia"></span></li>
        <li><b>Mã người dùng:</b> <span id="dg-manguoidung"></span></li>
        <li><b>Mã hóa đơn:</b> <span id="dg-mahd"></span></li>
    </ul>
    <button class="dg-btn dg-back">Quay lại</button>
</div>
<script>
// Gắn dữ liệu từ view cha (nếu có)
const madg = document.querySelector('[name="madg"]') ? document.querySelector('[name="madg"]').value : '';
document.getElementById('dg-madg').textContent = madg;
// Các trường còn lại sẽ được gán động nếu cần (hoặc có thể fetch chi tiết nếu muốn nâng cao)
</script> 