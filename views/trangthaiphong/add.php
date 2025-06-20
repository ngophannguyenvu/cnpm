<style>
.ttp-form {
    max-width: 400px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.ttp-form-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.ttp-form label {
    color: #ff4081;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.ttp-form input {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 16px;
    border: 1px solid #ffb6d5;
    border-radius: 6px;
    font-size: 1rem;
    background: #fff;
}
.ttp-form .ttp-btn {
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
.ttp-form .ttp-btn:hover {
    background: #e73370;
}
.ttp-form .ttp-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.ttp-form .ttp-back:hover {
    background: #ffe4ec;
}
.ttp-form .ttp-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.ttp-form .ttp-msg.success { color: #43a047; }
.ttp-form .ttp-msg.error { color: #e53935; }
@media (max-width: 600px) {
    .ttp-form { padding: 10px; }
    .ttp-form-title { font-size: 1.1rem; }
}
</style>
<form class="ttp-form" id="ttp-add-form">
    <div class="ttp-form-title">Thêm trạng thái phòng mới</div>
    <div class="ttp-msg" id="ttp-add-msg"></div>
    <label for="tenttp">Tên trạng thái phòng</label>
    <input type="text" id="tenttp" name="tenttp" required>
    <button type="submit" class="ttp-btn">Thêm trạng thái phòng</button>
    <button type="button" class="ttp-btn ttp-back">Quay lại</button>
</form>
<script>
document.querySelector('.ttp-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
const ttpAddForm = document.getElementById('ttp-add-form');
const ttpAddMsg = document.getElementById('ttp-add-msg');
ttpAddForm.onsubmit = function(e) {
    e.preventDefault();
    ttpAddMsg.textContent = 'Đang xử lý...';
    ttpAddMsg.className = 'ttp-msg';
    fetch('http://localhost:86/cnpm-BE/api/trangthaiphong', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            TenTrangThaiPhong: ttpAddForm.tenttp.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success') {
            ttpAddMsg.textContent = 'Thêm trạng thái phòng thành công!';
            ttpAddMsg.className = 'ttp-msg success';
            setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
        } else {
            ttpAddMsg.textContent = data.message || 'Thêm trạng thái phòng thất bại!';
            ttpAddMsg.className = 'ttp-msg error';
        }
    })
    .catch(() => {
        ttpAddMsg.textContent = 'Lỗi kết nối máy chủ!';
        ttpAddMsg.className = 'ttp-msg error';
    });
};
</script> 