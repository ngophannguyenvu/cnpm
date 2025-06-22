function initAddPhuongThucForm() {
    const ptAddForm = document.getElementById('pt-add-form');
    const ptAddMsg = document.getElementById('pt-add-msg') || document.querySelector('.pt-form-msg');
    if (!ptAddForm) return;
    ptAddForm.onsubmit = function(e) {
        e.preventDefault();
        const mapt = this.mapt.value.trim();
        const tenpt = this.tenpt.value.trim();
        const mota = this.mota.value.trim();
        ptAddMsg.textContent = '';
        ptAddMsg.className = 'pt-form-msg';
        if (!mapt || !tenpt || !mota) {
            ptAddMsg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
            ptAddMsg.classList.add('error');
            return;
        }
        fetch('http://localhost:86/cnpm-be/api/phuongthuc', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ MaPT: mapt, TenPT: tenpt, Mota: mota })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message && data.message.toLowerCase().includes('success')) {
                ptAddMsg.textContent = 'Thêm phương thức thành công!';
                ptAddMsg.className = 'pt-form-msg success';
                setTimeout(() => {
                    if (typeof backToMain === 'function') backToMain();
                    if (typeof fetchPhuongThuc === 'function') fetchPhuongThuc();
                }, 1000);
            } else {
                ptAddMsg.textContent = data.error || data.message || 'Thêm phương thức thất bại!';
                ptAddMsg.className = 'pt-form-msg error';
            }
        })
        .catch(() => {
            ptAddMsg.textContent = 'Lỗi kết nối máy chủ!';
            ptAddMsg.className = 'pt-form-msg error';
        });
    };
    // Nút quay lại
    const backBtn = ptAddForm.querySelector('.pt-back');
    if (backBtn) {
        backBtn.onclick = function() {
            if (typeof backToMain === 'function') backToMain();
        };
    }
} 