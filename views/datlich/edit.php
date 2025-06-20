<style>
.dl-form-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
}
.dl-form-title {
    color: #ff4081;
    text-align: center;
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.dl-form label {
    display: block;
    margin-bottom: 10px;
    color: #d81b60;
    font-weight: 500;
}
.dl-form input[type="text"],
.dl-form input[type="datetime-local"] {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ff80ab;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 1rem;
    background: #fff;
    transition: border 0.2s;
}
.dl-form input[type="text"]:focus,
.dl-form input[type="datetime-local"]:focus {
    border: 1.5px solid #ff4081;
    outline: none;
}
.dl-form input[readonly] {
    background: #ffe4ec;
    color: #b71c5c;
}
.dl-form-btns {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dl-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 20px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.dl-btn:hover { background: #e73370; }
.dl-btn.dl-back { background: #ff80ab; }
.dl-form-msg {
    text-align: center;
    margin-top: 12px;
    font-weight: 500;
}
.dl-form-msg.success { color: #43a047; }
.dl-form-msg.error { color: #d32f2f; }
</style>
<div class="dl-form-wrap">
    <div class="dl-form-title">Sửa Đặt lịch</div>
    <form class="dl-form" id="dl-edit-form" autocomplete="off">
        <label>Mã ĐL:
            <input type="text" name="madl" required readonly>
        </label>
        <label>Mã người dùng:
            <input type="text" name="manguoidung" required placeholder="Nhập mã người dùng">
        </label>
        <label>Thời gian đặt lịch:
            <input type="datetime-local" name="thoigiandatlich" required>
        </label>
        <label>Trạng thái:
            <input type="text" name="trangthai" required placeholder="Nhập trạng thái">
        </label>
        <div class="dl-form-btns">
            <button type="submit" class="dl-btn">Lưu thay đổi</button>
            <button type="button" class="dl-btn dl-back">Quay lại</button>
        </div>
        <div class="dl-form-msg" id="dl-edit-msg"></div>
    </form>
</div>
<script>
// Lấy madl từ query parameter hoặc input ẩn
const urlParams = new URLSearchParams(window.location.search);
const madl = urlParams.get('madl') || (document.querySelector('[name="madl"]')?.value || '');

if (!madl) {
    document.getElementById('dl-del-msg').textContent = 'Mã ĐL không hợp lệ!';
    document.getElementById('dl-del-msg').classList.add('error');
    return;
}

document.getElementById('dl-del-madl').textContent = madl;

document.getElementById('dl-del-confirm').onclick = function() {
    const msg = document.getElementById('dl-del-msg');
    msg.textContent = '';
    msg.className = 'dl-del-msg';

    fetch('http://localhost:86/cnpm-BE/api/datlich/' + encodeURIComponent(madl), {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
        }
        return res.json();
    })
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
    .catch(error => {
        msg.textContent = error.message.includes('Failed to fetch') ? 'Lỗi kết nối máy chủ hoặc vấn đề CORS!' : error.message;
        msg.classList.add('error');
    });
};
</script> 