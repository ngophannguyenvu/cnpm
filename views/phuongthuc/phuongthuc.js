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
        fetch('http://localhost:81/cnpm/api/phuongthuc', {
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

function initEditPhuongThucForm() {
    const form = document.getElementById('pt-edit-form');
    const msg = document.getElementById('pt-edit-msg');
    if (!form) return;
    const mapt = form.mapt.value;
    if (typeof _ptData !== 'undefined' && mapt) {
        const pt = (_ptData || []).find(x => x.MaPT == mapt);
        if (pt) {
            form.mapt.value = pt.MaPT || '';
            form.tenpt.value = pt.TenPT || '';
            form.mota.value = pt.Mota || '';
            // Hiển thị thông tin cũ
            const oldInfo = document.getElementById('pt-old-info');
            if (oldInfo) {
                oldInfo.style.display = '';
                document.getElementById('pt-old-mapt').textContent = pt.MaPT || '';
                document.getElementById('pt-old-tenpt').textContent = pt.TenPT || '';
                document.getElementById('pt-old-mota').textContent = pt.Mota || '';
            }
        }
    }
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'pt-form-msg';
        fetch('http://localhost:81/cnpm/api/phuongthuc/' + form.mapt.value, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                TenPT: form.tenpt.value,
                Mota: form.mota.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message && data.message.toLowerCase().includes('success')) {
                msg.textContent = data.message || 'Cập nhật phương thức thành công!';
                msg.className = 'pt-form-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchPhuongThuc === 'function') fetchPhuongThuc(); }, 1000);
            } else {
                msg.textContent = data.error || data.message || 'Cập nhật thất bại!';
                msg.className = 'pt-form-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'pt-form-msg error';
        });
    };
    // Nút quay lại
    const backBtn = form.querySelector('.pt-back');
    if (backBtn) {
        backBtn.onclick = function() {
            if (typeof backToMain === 'function') backToMain();
        };
    }
}
window.initEditPhuongThucForm = initEditPhuongThucForm; 