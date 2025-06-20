<style>
.hd-del-box {
    max-width: 400px;
    margin: 60px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
    text-align: center;
}
.hd-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.hd-del-msg {
    margin-bottom: 16px;
    font-size: 1rem;
}
.hd-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    margin: 0 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.hd-btn:hover {
    background: #e73370;
}
.hd-btn.hd-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
}
.hd-btn.hd-back:hover {
    background: #ffe4ec;
}
.hd-msg.success { color: #43a047; }
.hd-msg.error { color: #e53935; }
</style>
<div class="hd-del-box">
    <div class="hd-del-title">Xác nhận xoá hóa đơn</div>
    <div class="hd-del-msg">Bạn có chắc chắn muốn xoá hóa đơn này không?</div>
    <input type="hidden" name="mahd">
    <div class="hd-msg" id="hd-del-msg"></div>
    <button class="hd-btn hd-confirm">Xoá</button>
    <button class="hd-btn hd-back">Quay lại</button>
</div>
<script>
document.querySelector('.hd-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const hdDelMsg = document.getElementById('hd-del-msg');
const mahdInput = document.querySelector('input[name="mahd"]');
document.querySelector('.hd-confirm').onclick = function() {
    hdDelMsg.textContent = 'Đang xử lý...';
    hdDelMsg.className = 'hd-msg';
    fetch('http://localhost:86/cnpm-BE/api/hoadonvathanhtoan/' + mahdInput.value, {
        method: 'DELETE'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success') {
            hdDelMsg.textContent = 'Xoá hóa đơn thành công!';
            hdDelMsg.className = 'hd-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            hdDelMsg.textContent = data.message || 'Xoá thất bại!';
            hdDelMsg.className = 'hd-msg error';
        }
    })
    .catch(() => {
        hdDelMsg.textContent = 'Lỗi kết nối máy chủ!';
        hdDelMsg.className = 'hd-msg error';
    });
};
</script> 