function initAddTrangThaiPhongForm() {
    const form = document.getElementById('ttp-add-form');
    const msg = document.getElementById('ttp-add-msg');
    if (!form) return;
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'ttp-msg';
        fetch('http://localhost:86/cnpm-be/api/trangthaiphong', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                MatrangthaiP: form.mattp.value,
                Tentrangthai: form.tenttp.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success' || data.message) {
                msg.textContent = data.message || 'Thêm trạng thái phòng thành công!';
                msg.className = 'ttp-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); fetchTrangThaiPhong(); }, 1000);
            } else {
                msg.textContent = data.message || 'Thêm trạng thái phòng thất bại!';
                msg.className = 'ttp-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'ttp-msg error';
        });
    };
}

function loadTrangThaiPhongView(view, mttp = '') {
    fetch(`views/trangthaiphong/${view}.php`)
        .then(res => res.text())
        .then(html => {
            ttpContent.innerHTML = html;
            ttpTableWrap.style.display = 'none';
            ttpContent.scrollIntoView({behavior: 'smooth'});
            if (view === 'add' && typeof initAddTrangThaiPhongForm === 'function') {
                initAddTrangThaiPhongForm();
            }
            if (view === 'edit' && typeof window.initEditTrangThaiPhongForm === 'function') {
                window.initEditTrangThaiPhongForm();
            }
            if (view !== 'add' && mttp) {
                document.querySelectorAll('[name="mttp"]').forEach(e => e.value = mttp);
                if (view === 'detail') {
                    document.getElementById('ttp-mttp').textContent = mttp;
                }
            }
            if (view === 'delete' && mttp) {
                document.querySelectorAll('[name="mttp"]').forEach(e => e.value = mttp);
                // Gắn lại sự kiện cho nút Xoá và Quay lại
                const ttpBackBtn = document.querySelector('.ttp-back');
                if (ttpBackBtn) {
                    ttpBackBtn.onclick = function() {
                        if (typeof backToMain === 'function') backToMain();
                    };
                }
                const ttpConfirmBtn = document.querySelector('.ttp-confirm');
                const ttpDelMsg = document.getElementById('ttp-del-msg');
                const mttpInput = document.querySelector('input[name="mttp"]');
                if (ttpConfirmBtn && mttpInput) {
                    ttpConfirmBtn.onclick = function() {
                        ttpDelMsg.textContent = 'Đang xử lý...';
                        ttpDelMsg.className = 'ttp-msg';
                        fetch('http://localhost:86/cnpm-be/api/trangthaiphong/' + mttpInput.value, {
                            method: 'DELETE'
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success || data.status === 'success' || data.message) {
                                ttpDelMsg.textContent = data.message || 'Xoá trạng thái phòng thành công!';
                                ttpDelMsg.className = 'ttp-msg success';
                                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchTrangThaiPhong === 'function') fetchTrangThaiPhong(); }, 1000);
                            } else {
                                ttpDelMsg.textContent = data.message || 'Xoá thất bại!';
                                ttpDelMsg.className = 'ttp-msg error';
                            }
                        })
                        .catch(() => {
                            ttpDelMsg.textContent = 'Lỗi kết nối máy chủ!';
                            ttpDelMsg.className = 'ttp-msg error';
                        });
                    };
                }
            }
        });
}

window.initAddTrangThaiPhongForm = initAddTrangThaiPhongForm; 