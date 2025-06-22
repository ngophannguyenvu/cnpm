function initAddCTDVForm() {
    const ctdvAddForm = document.getElementById('ctdv-add-form');
    const ctdvAddMsg = document.getElementById('ctdv-add-msg') || document.querySelector('.ctdv-form-msg');
    if (!ctdvAddForm) return;
    ctdvAddForm.onsubmit = function(e) {
        e.preventDefault();
        const madl = this.madl.value.trim();
        const madv = this.madv.value.trim();
        ctdvAddMsg.textContent = '';
        ctdvAddMsg.className = 'ctdv-form-msg';
        if (!madl || !madv) {
            ctdvAddMsg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
            ctdvAddMsg.classList.add('error');
            return;
        }
        fetch('http://localhost:86/cnpm-be/api/chitietdichvu', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ MaDL: madl, MaDV: madv })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message && data.message.toLowerCase().includes('success')) {
                ctdvAddMsg.textContent = 'Thêm chi tiết dịch vụ thành công!';
                ctdvAddMsg.className = 'ctdv-form-msg success';
                setTimeout(() => {
                    if (typeof backToMain === 'function') backToMain();
                    if (typeof fetchCTDV === 'function') fetchCTDV();
                }, 1000);
            } else {
                ctdvAddMsg.textContent = data.error || data.message || 'Thêm chi tiết dịch vụ thất bại!';
                ctdvAddMsg.className = 'ctdv-form-msg error';
            }
        })
        .catch(() => {
            ctdvAddMsg.textContent = 'Lỗi kết nối máy chủ!';
            ctdvAddMsg.className = 'ctdv-form-msg error';
        });
    };
    // Nút quay lại
    const backBtn = ctdvAddForm.querySelector('.ctdv-back');
    if (backBtn) {
        backBtn.onclick = function() {
            if (typeof backToMain === 'function') backToMain();
        };
    }
} 