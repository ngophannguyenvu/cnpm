<style>
.ctdv-del-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
    text-align: center;
}
.ctdv-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.ctdv-del-info {
    color: #d81b60;
    margin-bottom: 18px;
    font-size: 1.1rem;
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
.ctdv-del-msg {
    margin-top: 14px;
    font-weight: 500;
}
.ctdv-del-msg.success { color: #43a047; }
.ctdv-del-msg.error { color: #d32f2f; }
</style>
<div class="ctdv-del-wrap">
    <div class="ctdv-del-title">Xoá Chi tiết Dịch vụ</div>
    <div class="ctdv-del-info">Bạn có chắc chắn muốn xoá chi tiết dịch vụ <b><span id="ctdv-del-madl"></span> - <span id="ctdv-del-madv"></span></b> không?</div>
    <input type="hidden" name="madl">
    <input type="hidden" name="madv">
    <button class="ctdv-btn" id="ctdv-del-confirm">Xoá</button>
    <button class="ctdv-btn ctdv-back">Huỷ</button>
    <div class="ctdv-del-msg" id="ctdv-del-msg"></div>
</div>
<script>
// Gắn dữ liệu từ view cha
const madl = document.querySelector('[name="madl"]').value;
const madv = document.querySelector('[name="madv"]').value;
document.getElementById('ctdv-del-madl').textContent = madl;
document.getElementById('ctdv-del-madv').textContent = madv;

document.getElementById('ctdv-del-confirm').onclick = function() {
    const msg = document.getElementById('ctdv-del-msg');
    msg.textContent = '';
    msg.className = 'ctdv-del-msg';
    fetch('http://localhost:86/cnpm-BE/api/chitietdichvu/' + encodeURIComponent(madl), {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ MaDV: madv })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            msg.textContent = data.message;
            msg.classList.add('success');
            setTimeout(() => document.querySelector('.ctdv-back').click(), 1000);
        } else {
            msg.textContent = data.error || 'Xoá thất bại!';
            msg.classList.add('error');
        }
    })
    .catch(() => {
        msg.textContent = 'Lỗi kết nối máy chủ!';
        msg.classList.add('error');
    });
};
</script> 