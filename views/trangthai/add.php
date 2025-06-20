<style>
.tt-form {
    max-width: 400px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.tt-form-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.tt-form label {
    color: #ff4081;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.tt-form input {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 16px;
    border: 1px solid #ffb6d5;
    border-radius: 6px;
    font-size: 1rem;
    background: #fff;
}
.tt-form .tt-btn {
    width: 100%;
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 10px 0;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    margin-bottom: 8px;
    transition: background 0.2s;
}
.tt-form .tt-btn:hover {
    background: #e73370;
}
.tt-form .tt-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.tt-form .tt-back:hover {
    background: #ffe4ec;
}
.tt-form .tt-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.tt-form .tt-msg.success { color: #43a047; }
.tt-form .tt-msg.error { color: #e53935; }
@media (max-width: 600px) {
    .tt-form { padding: 10px; }
    .tt-form-title { font-size: 1.1rem; }
}
</style>
<form class="tt-form" id="tt-add-form">
    <div class="tt-form-title">Thêm trạng thái mới</div>
    <div class="tt-msg" id="tt-add-msg"></div>
    <label for="tentt">Tên trạng thái</label>
    <input type="text" id="tentt" name="tentt" required>
    <button type="submit" class="tt-btn">Thêm trạng thái</button>
    <button type="button" class="tt-btn tt-back">Quay lại</button>
</form>
<script>
document.querySelector('.tt-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const ttAddForm = document.getElementById('tt-add-form');
const ttAddMsg = document.getElementById('tt-add-msg');
ttAddForm.onsubmit = function(e) {
    e.preventDefault();
    ttAddMsg.textContent = 'Đang xử lý...';
    ttAddMsg.className = 'tt-msg';
    fetch('http://localhost:86/cnpm-BE/api/trangthai', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            TenTrangThai: ttAddForm.tentt.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success') {
            ttAddMsg.textContent = 'Thêm trạng thái thành công!';
            ttAddMsg.className = 'tt-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            ttAddMsg.textContent = data.message || 'Thêm trạng thái thất bại!';
            ttAddMsg.className = 'tt-msg error';
        }
    })
    .catch(() => {
        ttAddMsg.textContent = 'Lỗi kết nối máy chủ!';
        ttAddMsg.className = 'tt-msg error';
    });
};
</script> 