<style>
.dv-del-box { max-width: 400px; margin: 60px auto; background: #fff0f6; border-radius: 16px; box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15); padding: 32px 24px 24px 24px; font-family: 'Segoe UI', Arial, sans-serif; text-align: center; }
.dv-del-title { color: #ff4081; font-size: 1.3rem; font-weight: bold; margin-bottom: 18px; }
.dv-del-msg { margin-bottom: 16px; font-size: 1rem; }
.dv-btn { background: #ff4081; color: #fff; border: none; border-radius: 6px; padding: 8px 16px; margin: 0 6px; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
.dv-btn:hover { background: #e73370; }
.dv-btn.dv-back { background: #fff; color: #ff4081; border: 1px solid #ff80ab; }
.dv-btn.dv-back:hover { background: #ffe4ec; }
.dv-msg.success { color: #43a047; }
.dv-msg.error { color: #e53935; }
</style>
<div class="dv-del-box">
    <div class="dv-del-title">Xác nhận xoá dịch vụ</div>
    <div class="dv-del-msg">Bạn có chắc chắn muốn xoá dịch vụ này không?</div>
    <input type="hidden" name="madv">
    <div class="dv-msg" id="dv-del-msg"></div>
    <button class="dv-btn dv-confirm">Xoá</button>
    <button class="dv-btn dv-back">Quay lại</button>
</div>
<script>
document.querySelector('.dv-back').onclick = function() {
    if (typeof backToDVMain === 'function') backToDVMain();
};
const dvDelMsg = document.getElementById('dv-del-msg');
const madvInput = document.querySelector('input[name="madv"]');
document.querySelector('.dv-confirm').onclick = function() {
    dvDelMsg.textContent = 'Đang xử lý...';
    dvDelMsg.className = 'dv-msg';
    fetch('http://localhost:86/cnpm-be/api/dichvu/' + madvInput.value, {
        method: 'DELETE'
    })
    .then(res => res.json())
    .then(data => {
        if (data.message === 'Xóa dịch vụ thành công') {
            dvDelMsg.textContent = 'Xoá dịch vụ thành công!';
            dvDelMsg.className = 'dv-msg success';
            setTimeout(() => { if (typeof backToDVMain === 'function') backToDVMain(); }, 1000);
        } else {
            dvDelMsg.textContent = data.message || 'Xoá thất bại!';
            dvDelMsg.className = 'dv-msg error';
        }
    })
    .catch(() => {
        dvDelMsg.textContent = 'Lỗi kết nối máy chủ!';
        dvDelMsg.className = 'dv-msg error';
    });
};
</script> 