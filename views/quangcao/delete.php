<style>
.qc-del-box {
    max-width: 400px;
    margin: 60px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
    text-align: center;
}
.qc-del-title {
    color: #ff4081;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.qc-del-msg {
    margin-bottom: 16px;
    font-size: 1rem;
}
.qc-btn {
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
.qc-btn:hover {
    background: #e73370;
}
.qc-btn.qc-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
}
.qc-btn.qc-back:hover {
    background: #ffe4ec;
}
.qc-msg.success { color: #43a047; }
.qc-msg.error { color: #e53935; }
</style>
<div class="qc-del-box">
    <div class="qc-del-title">Xác nhận xoá quảng cáo</div>
    <div class="qc-del-msg">Bạn có chắc chắn muốn xoá quảng cáo này không?</div>
    <input type="hidden" name="maqc">
    <div class="qc-msg" id="qc-del-msg"></div>
    <button class="qc-btn qc-confirm">Xoá</button>
    <button class="qc-btn qc-back">Quay lại</button>
</div>
<script>
document.querySelector('.qc-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const qcDelMsg = document.getElementById('qc-del-msg');
const maqcInput = document.querySelector('input[name="maqc"]');
document.querySelector('.qc-confirm').onclick = function() {
    qcDelMsg.textContent = 'Đang xử lý...';
    qcDelMsg.className = 'qc-msg';
    fetch('http://localhost:86/cnpm-BE/api/quangcao/' + maqcInput.value, {
        method: 'DELETE'
    })
    .then(res => res.json())
    .then(data => {
        if (data.message || data.success || data.status === 'success') {
            qcDelMsg.textContent = data.message || 'Xoá quảng cáo thành công!';
            qcDelMsg.className = 'qc-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            qcDelMsg.textContent = data.message || 'Xoá thất bại!';
            qcDelMsg.className = 'qc-msg error';
        }
    })
    .catch(() => {
        qcDelMsg.textContent = 'Lỗi kết nối máy chủ!';
        qcDelMsg.className = 'qc-msg error';
    });
};
</script> 