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

function initEditPhongForm() {
    const form = document.getElementById('phong-edit-form');
    const msg = document.getElementById('phong-edit-msg');
    if (!form) return;
    const maphong = form.maphong.value;
    if (typeof _phongData !== 'undefined' && maphong) {
        const phong = (_phongData || []).find(x => x.Maphong == maphong);
        if (phong) {
            form.tenphong.value = phong.Tenphong || '';
            form.loaiphong.value = phong.Loaiphong || '';
            form.matrangthaiP.value = phong.MatrangthaiP || '';
            // Hiển thị thông tin cũ
            const oldInfo = document.getElementById('phong-old-info');
            if (oldInfo) {
                oldInfo.style.display = '';
                document.getElementById('phong-old-maphong').textContent = phong.Maphong || '';
                document.getElementById('phong-old-tenphong').textContent = phong.Tenphong || '';
                document.getElementById('phong-old-loaiphong').textContent = phong.Loaiphong || '';
                document.getElementById('phong-old-matrangthaiP').textContent = phong.MatrangthaiP || '';
            }
        }
    }
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'phong-msg';
        fetch('http://localhost:86/cnpm-be/api/phong/' + form.maphong.value, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Tenphong: form.tenphong.value,
                Loaiphong: form.loaiphong.value,
                MatrangthaiP: form.matrangthaiP.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success' || data.message) {
                msg.textContent = data.message || 'Cập nhật phòng thành công!';
                msg.className = 'phong-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchPhong === 'function') fetchPhong(); }, 1000);
            } else {
                msg.textContent = data.message || 'Cập nhật thất bại!';
                msg.className = 'phong-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'phong-msg error';
        });
    };
}
window.initEditPhongForm = initEditPhongForm;

function loadPhongView(view, maphong = '') {
    fetch(`views/phong/${view}.php`)
        .then(res => res.text())
        .then(html => {
            phongContent.innerHTML = html;
            phongTableWrap.style.display = 'none';
            phongContent.scrollIntoView({behavior: 'smooth'});
            if (view === 'add' && typeof initAddPhongForm === 'function') {
                initAddPhongForm();
            }
            if (view === 'edit' && typeof window.initEditPhongForm === 'function') {
                window.initEditPhongForm();
            }
            if (view !== 'add' && maphong) {
                document.querySelectorAll('[name="maphong"]').forEach(e => e.value = maphong);
                if (view === 'detail') {
                    document.getElementById('phong-maphong').textContent = maphong;
                }
            }
            if (view === 'delete' && maphong) {
                document.querySelectorAll('[name="maphong"]').forEach(e => e.value = maphong);
                // Gắn lại sự kiện cho nút Xoá và Quay lại
                const phongBackBtn = document.querySelector('.phong-back');
                if (phongBackBtn) {
                    phongBackBtn.onclick = function() {
                        if (typeof backToMain === 'function') backToMain();
                    };
                }
                const phongConfirmBtn = document.querySelector('.phong-confirm');
                const phongDelMsg = document.getElementById('phong-del-msg');
                const maphongInput = document.querySelector('input[name="maphong"]');
                if (phongConfirmBtn && maphongInput) {
                    phongConfirmBtn.onclick = function() {
                        phongDelMsg.textContent = 'Đang xử lý...';
                        phongDelMsg.className = 'phong-msg';
                        fetch('http://localhost:86/cnpm-be/api/phong/' + maphongInput.value, {
                            method: 'DELETE'
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success || data.status === 'success' || data.message) {
                                phongDelMsg.textContent = data.message || 'Xoá phòng thành công!';
                                phongDelMsg.className = 'phong-msg success';
                                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchPhong === 'function') fetchPhong(); }, 1000);
                            } else {
                                phongDelMsg.textContent = data.message || 'Xoá thất bại!';
                                phongDelMsg.className = 'phong-msg error';
                            }
                        })
                        .catch(() => {
                            phongDelMsg.textContent = 'Lỗi kết nối máy chủ!';
                            phongDelMsg.className = 'phong-msg error';
                        });
                    };
                }
            }
        });
} 