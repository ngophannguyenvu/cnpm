<style>
.dg-del-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
    text-align: center;
}
.dg-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.dg-del-info {
    color: #d81b60;
    margin-bottom: 18px;
    font-size: 1.1rem;
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
.dg-del-msg {
    margin-top: 14px;
    font-weight: 500;
}
.dg-del-msg.success { color: #43a047; }
.dg-del-msg.error { color: #d32f2f; }
</style>
<div class="dg-del-wrap">
    <div class="dg-del-title">Xoá Đánh giá</div>
    <div class="dg-del-info">Bạn có chắc chắn muốn xoá đánh giá <b><span id="dg-del-madg"></span></b> không?</div>
    <input type="hidden" name="madg">
    <button class="dg-btn" id="dg-del-confirm">Xoá</button>
    <button class="dg-btn dg-back">Huỷ</button>
    <div class="dg-del-msg" id="dg-del-msg"></div>
</div>
<script>
// Gắn dữ liệu từ view cha
const madg = document.querySelector('[name="madg"]').value;
document.getElementById('dg-del-madg').textContent = madg;

document.getElementById('dg-del-confirm').onclick = function() {
    const msg = document.getElementById('dg-del-msg');
    msg.textContent = '';
    msg.className = 'dg-del-msg';
    fetch('http://localhost:86/cnpm-BE/api/danhgia/' + encodeURIComponent(madg), {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            msg.textContent = data.message;
            msg.classList.add('success');
            setTimeout(() => document.querySelector('.dg-back').click(), 1000);
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