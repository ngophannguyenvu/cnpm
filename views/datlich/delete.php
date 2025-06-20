<style>
.dl-del-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
    text-align: center;
}
.dl-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.dl-del-info {
    color: #d81b60;
    margin-bottom: 18px;
    font-size: 1.1rem;
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
.dl-del-msg {
    margin-top: 14px;
    font-weight: 500;
}
.dl-del-msg.success { color: #43a047; }
.dl-del-msg.error { color: #d32f2f; }
</style>
<div class="dl-del-wrap">
    <div class="dl-del-title">Xoá Đặt lịch</div>
    <div class="dl-del-info">Bạn có chắc chắn muốn xoá đặt lịch <b><span id="dl-del-madl"></span></b> không?</div>
    <input type="hidden" name="madl">
    <button class="dl-btn" id="dl-del-confirm">Xoá</button>
    <button class="dl-btn dl-back">Huỷ</button>
    <div class="dl-del-msg" id="dl-del-msg"></div>
</div>
<script>
// Gắn dữ liệu từ view cha
const madl = document.querySelector('[name="madl"]').value;
document.getElementById('dl-del-madl').textContent = madl;

document.getElementById('dl-del-confirm').onclick = function() {
    const msg = document.getElementById('dl-del-msg');
    msg.textContent = '';
    msg.className = 'dl-del-msg';
    fetch('http://localhost:86/cnpm-BE/api/datlich/' + encodeURIComponent(madl), {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            msg.textContent = data.message;
            msg.classList.add('success');
            setTimeout(() => document.querySelector('.dl-back').click(), 1000);
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