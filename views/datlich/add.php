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
    <div class="dl-form-title">Thêm Đặt lịch</div>
    <form class="dl-form" id="dl-add-form" autocomplete="off">
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
            <button type="submit" class="dl-btn">Thêm</button>
            <button type="button" class="dl-btn dl-back" onclick="window.history.back()">Quay lại</button>
        </div>
        <div class="dl-form-msg" id="dl-add-msg"></div>
    </form>
</div>

<script>
document.getElementById('dl-add-form').onsubmit = function(e) {
    e.preventDefault();
    const manguoidung = this.manguoidung.value.trim();
    const thoigiandatlichInput = this.thoigiandatlich.value;
    const trangthai = this.trangthai.value.trim();
    const msg = document.getElementById('dl-add-msg');

    msg.textContent = '';
    msg.className = 'dl-form-msg';

    // Kiểm tra đầu vào
    if (!manguoidung || !thoigiandatlichInput || !trangthai) {
        msg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
        msg.classList.add('error');
        return;
    }

    // Chuyển đổi định dạng Thoigiandatlich sang Y-m-d H:i:s
    let thoigiandatlich;
    try {
        thoigiandatlich = new Date(thoigiandatlichInput).toISOString().slice(0, 19).replace('T', ' ');
    } catch (error) {
        msg.textContent = 'Định dạng thời gian không hợp lệ!';
        msg.classList.add('error');
        return;
    }

    // Gửi API POST
    fetch('http://localhost:86/cnpm-BE/api/datlich', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            Manguoidung: manguoidung, // Gửi dưới dạng chuỗi
            Thoigiandatlich: thoigiandatlich,
            Trangthai: trangthai
        })
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
            setTimeout(() => window.location.reload(), 1000);
        } else {
            msg.textContent = data.error || (data.errors ? Object.values(data.errors).join(', ') : 'Thêm thất bại!');
            msg.classList.add('error');
        }
    })
    .catch(error => {
        msg.textContent = error.message.includes('Failed to fetch') ? 'Lỗi kết nối máy chủ hoặc vấn đề CORS!' : error.message;
        msg.classList.add('error');
    });



    // Gửi API POST
    fetch('http://localhost:86/cnpm-BE/api/datlich', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            Manguoidung: manguoidung,
            Thoigiandatlich: thoigiandatlich,
            Trangthai: trangthai
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            msg.textContent = data.message;
            msg.classList.add('success');
            setTimeout(() => window.location.reload(), 1000); // hoặc redirect lại danh sách
        } else {
            msg.textContent = data.error || (data.errors ? Object.values(data.errors).join(', ') : 'Thêm thất bại!');
            msg.classList.add('error');
        }
    })
    .catch(() => {
        msg.textContent = 'Lỗi kết nối máy chủ!';
        msg.classList.add('error');
    });
};
</script>
