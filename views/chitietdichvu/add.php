<style>
.ctdv-form-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
}
.ctdv-form-title {
    color: #ff4081;
    text-align: center;
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.ctdv-form label {
    display: block;
    margin-bottom: 10px;
    color: #d81b60;
    font-weight: 500;
}
.ctdv-form input[type="text"] {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ff80ab;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 1rem;
    background: #fff;
    transition: border 0.2s;
}
.ctdv-form input[type="text"]:focus {
    border: 1.5px solid #ff4081;
    outline: none;
}
.ctdv-form-btns {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ctdv-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 20px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.ctdv-btn:hover { background: #e73370; }
.ctdv-btn.ctdv-back { background: #ff80ab; }
.ctdv-form-msg {
    text-align: center;
    margin-top: 12px;
    font-weight: 500;
}
.ctdv-form-msg.success { color: #43a047; }
.ctdv-form-msg.error { color: #d32f2f; }
</style>
<div class="ctdv-form-wrap">
    <div class="ctdv-form-title">Thêm Chi tiết Dịch vụ</div>
    <form class="ctdv-form" id="ctdv-add-form" autocomplete="off">
        <label>Mã ĐL:
            <input type="text" name="madl" required placeholder="Nhập mã ĐL">
        </label>
        <label>Mã DV:
            <input type="text" name="madv" required placeholder="Nhập mã DV">
        </label>
        <div class="ctdv-form-btns">
            <button type="submit" class="ctdv-btn">Thêm</button>
            <button type="button" class="ctdv-btn ctdv-back">Quay lại</button>
        </div>
        <div class="ctdv-form-msg" id="ctdv-add-msg"></div>
    </form>
</div>
<script>
document.getElementById('ctdv-add-form').onsubmit = function(e) {
    e.preventDefault();
    const madl = this.madl.value.trim();
    const madv = this.madv.value.trim();
    const msg = document.getElementById('ctdv-add-msg');
    msg.textContent = '';
    msg.className = 'ctdv-form-msg';
    if (!madl || !madv) {
        msg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
        msg.classList.add('error');
        return;
    }
    fetch('http://localhost:86/cnpm-BE/api/chitietdichvu', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ MaDL: madl, MaDV: madv })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            msg.textContent = data.message;
            msg.classList.add('success');
            setTimeout(() => document.querySelector('.ctdv-back').click(), 1000);
        } else {
            msg.textContent = data.error || 'Thêm thất bại!';
            msg.classList.add('error');
        }
    })
    .catch(() => {
        msg.textContent = 'Lỗi kết nối máy chủ!';
        msg.classList.add('error');
    });
};
</script> 