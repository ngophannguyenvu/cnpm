<style>
.dg-form-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
}
.dg-form-title {
    color: #ff4081;
    text-align: center;
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.dg-form label {
    display: block;
    margin-bottom: 10px;
    color: #d81b60;
    font-weight: 500;
}
.dg-form input[type="text"],
.dg-form input[type="number"],
.dg-form input[type="date"] {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ff80ab;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 1rem;
    background: #fff;
    transition: border 0.2s;
}
.dg-form input[type="text"]:focus,
.dg-form input[type="number"]:focus,
.dg-form input[type="date"]:focus {
    border: 1.5px solid #ff4081;
    outline: none;
}
.dg-form-btns {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dg-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 20px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.dg-btn:hover { background: #e73370; }
.dg-btn.dg-back { background: #ff80ab; }
.dg-form-msg {
    text-align: center;
    margin-top: 12px;
    font-weight: 500;
}
.dg-form-msg.success { color: #43a047; }
.dg-form-msg.error { color: #d32f2f; }
</style>
<div class="dg-form-wrap">
    <div class="dg-form-title">Thêm Đánh giá</div>
    <form class="dg-form" id="dg-add-form" autocomplete="off">
        <label>Số sao:
            <input type="number" name="danhgiasao" min="1" max="5" required placeholder="Nhập số sao">
        </label>
        <label>Nhận xét:
            <input type="text" name="nhanxet" required placeholder="Nhận xét">
        </label>
        <label>Ngày đánh giá:
            <input type="date" name="ngaydanhgia" required>
        </label>
        <label>Mã người dùng:
            <input type="text" name="manguoidung" required placeholder="Nhập mã người dùng">
        </label>
        <label>Mã hóa đơn:
            <input type="text" name="mahd" required placeholder="Nhập mã hóa đơn">
        </label>
        <div class="dg-form-btns">
            <button type="submit" class="dg-btn">Thêm</button>
            <button type="button" class="dg-btn dg-back">Quay lại</button>
        </div>
        <div class="dg-form-msg" id="dg-add-msg"></div>
    </form>
</div>
<script>
document.getElementById('dg-add-form').onsubmit = function(e) {
    e.preventDefault();
    const danhgiasao = this.danhgiasao.value.trim();
    const nhanxet = this.nhanxet.value.trim();
    const ngaydanhgia = this.ngaydanhgia.value;
    const manguoidung = this.manguoidung.value.trim();
    const mahd = this.mahd.value.trim();
    const msg = document.getElementById('dg-add-msg');
    msg.textContent = '';
    msg.className = 'dg-form-msg';
    if (!danhgiasao || !nhanxet || !ngaydanhgia || !manguoidung || !mahd) {
        msg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
        msg.classList.add('error');
        return;
    }
    fetch('http://localhost:86/cnpm-BE/api/danhgia', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ Danhgiasao: danhgiasao, Nhanxet: nhanxet, Ngaydanhgia: ngaydanhgia, Manguoidung: manguoidung, MaHD: mahd })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            msg.textContent = data.message;
            msg.classList.add('success');
            setTimeout(() => document.querySelector('.dg-back').click(), 1000);
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