function initAddPhongForm() {
    const phongAddForm = document.getElementById('phong-add-form');
    const phongAddMsg = document.getElementById('phong-add-msg') || document.querySelector('.phong-form-msg');
    if (!phongAddForm) return;
    phongAddForm.onsubmit = function(e) {
        e.preventDefault();
        const tenphong = this.tenphong.value.trim();
        const loaiphong = this.loaiphong.value.trim();
        const matrangthaiP = this.matrangthaiP.value.trim();
        phongAddMsg.textContent = '';
        phongAddMsg.className = 'phong-form-msg';
        if (!tenphong || !loaiphong || !matrangthaiP) {
            phongAddMsg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
            phongAddMsg.classList.add('error');
            return;
        }
        fetch('http://localhost:86/cnpm-be/api/phong', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ Tenphong: tenphong, Loaiphong: loaiphong, MatrangthaiP: matrangthaiP })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message && data.message.toLowerCase().includes('success')) {
                phongAddMsg.textContent = 'Thêm phòng thành công!';
                phongAddMsg.className = 'phong-form-msg success';
                setTimeout(() => {
                    if (typeof backToMain === 'function') backToMain();
                    if (typeof fetchPhong === 'function') fetchPhong();
                }, 1000);
            } else {
                phongAddMsg.textContent = data.error || data.message || 'Thêm phòng thất bại!';
                phongAddMsg.className = 'phong-form-msg error';
            }
        })
        .catch(() => {
            phongAddMsg.textContent = 'Lỗi kết nối máy chủ!';
            phongAddMsg.className = 'phong-form-msg error';
        });
    };
} 