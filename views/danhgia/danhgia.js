function initAddDanhGiaForm() {
    const dgAddForm = document.getElementById('dg-add-form');
    const dgAddMsg = document.getElementById('dg-add-msg') || document.querySelector('.dg-form-msg');
    if (!dgAddForm) return;
    dgAddForm.onsubmit = function(e) {
        e.preventDefault();
        const danhgiasao = this.danhgiasao.value.trim();
        const nhanxet = this.nhanxet.value.trim();
        const ngaydanhgia = this.ngaydanhgia.value;
        const manguoidung = this.manguoidung.value.trim();
        const mahd = this.mahd.value.trim();
        dgAddMsg.textContent = '';
        dgAddMsg.className = 'dg-form-msg';
        if (!danhgiasao || !nhanxet || !ngaydanhgia || !manguoidung || !mahd) {
            dgAddMsg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
            dgAddMsg.classList.add('error');
            return;
        }
        fetch('http://localhost:86/cnpm-be/api/danhgia', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ Danhgiasao: danhgiasao, Nhanxet: nhanxet, Ngaydanhgia: ngaydanhgia, Manguoidung: manguoidung, MaHD: mahd })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message && data.message.toLowerCase().includes('success')) {
                dgAddMsg.textContent = 'Thêm đánh giá thành công!';
                dgAddMsg.className = 'dg-form-msg success';
                setTimeout(() => {
                    if (typeof backToMain === 'function') backToMain();
                    if (typeof fetchDanhGia === 'function') fetchDanhGia();
                }, 1000);
            } else {
                dgAddMsg.textContent = data.error || data.message || 'Thêm đánh giá thất bại!';
                dgAddMsg.className = 'dg-form-msg error';
            }
        })
        .catch(() => {
            dgAddMsg.textContent = 'Lỗi kết nối máy chủ!';
            dgAddMsg.className = 'dg-form-msg error';
        });
    };
}

function initEditDanhGiaForm() {
    const form = document.getElementById('dg-edit-form');
    const msg = document.getElementById('dg-edit-msg');
    if (!form) return;
    const madg = form.madg.value;
    if (typeof _dgData !== 'undefined' && madg) {
        const dg = (_dgData || []).find(x => x.MaDG == madg);
        if (dg) {
            form.madg.value = dg.MaDG || '';
            form.danhgiasao.value = dg.Danhgiasao || '';
            form.nhanxet.value = dg.Nhanxet || '';
            form.ngaydanhgia.value = dg.Ngaydanhgia || '';
            form.manguoidung.value = dg.Manguoidung || '';
            form.mahd.value = dg.MaHD || '';
            // Hiển thị thông tin cũ
            const oldInfo = document.getElementById('dg-old-info');
            if (oldInfo) {
                oldInfo.style.display = '';
                document.getElementById('dg-old-madg').textContent = dg.MaDG || '';
                document.getElementById('dg-old-danhgiasao').textContent = dg.Danhgiasao || '';
                document.getElementById('dg-old-nhanxet').textContent = dg.Nhanxet || '';
                document.getElementById('dg-old-ngaydanhgia').textContent = dg.Ngaydanhgia || '';
                document.getElementById('dg-old-manguoidung').textContent = dg.Manguoidung || '';
                document.getElementById('dg-old-mahd').textContent = dg.MaHD || '';
            }
        }
    }
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = '';
        msg.className = 'dg-form-msg';
        const danhgiasao = form.danhgiasao.value.trim();
        const nhanxet = form.nhanxet.value.trim();
        const ngaydanhgia = form.ngaydanhgia.value;
        const manguoidung = form.manguoidung.value.trim();
        const mahd = form.mahd.value.trim();
        if (!madg || !danhgiasao || !nhanxet || !ngaydanhgia || !manguoidung || !mahd) {
            msg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
            msg.classList.add('error');
            return;
        }
        fetch('http://localhost:86/cnpm-be/api/danhgia/' + form.madg.value, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Danhgiasao: danhgiasao,
                Nhanxet: nhanxet,
                Ngaydanhgia: ngaydanhgia,
                Manguoidung: manguoidung,
                MaHD: mahd
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message && data.message.toLowerCase().includes('success')) {
                msg.textContent = data.message || 'Cập nhật đánh giá thành công!';
                msg.className = 'dg-form-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchDanhGia === 'function') fetchDanhGia(); }, 1000);
            } else {
                msg.textContent = data.error || data.message || 'Cập nhật thất bại!';
                msg.className = 'dg-form-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'dg-form-msg error';
        });
    };
}
window.initEditDanhGiaForm = initEditDanhGiaForm;

function loadDGView(view, madg = '') {
    fetch(`views/danhgia/${view}.php`)
        .then(res => res.text())
        .then(html => {
            dgContent.innerHTML = html;
            dgTableWrap.style.display = 'none';
            dgContent.scrollIntoView({behavior: 'smooth'});
            if (view === 'add' && typeof initAddDanhGiaForm === 'function') {
                initAddDanhGiaForm();
            }
            if (view === 'edit' && typeof window.initEditDanhGiaForm === 'function') {
                window.initEditDanhGiaForm();
            }
            if (view !== 'add' && madg) {
                document.querySelectorAll('[name="madg"]').forEach(e => e.value = madg);
                if (view === 'detail') {
                    document.getElementById('dg-madg').textContent = madg;
                }
            }
            if (view === 'delete' && madg) {
                document.querySelectorAll('[name="madg"]').forEach(e => e.value = madg);
                setTimeout(() => {
                    const dgBackBtn = document.querySelector('.dg-back');
                    if (dgBackBtn) {
                        dgBackBtn.onclick = function() {
                            if (typeof backToMain === 'function') backToMain();
                        };
                    }
                    const dgConfirmBtn = document.getElementById('dg-del-confirm');
                    const dgDelMsg = document.getElementById('dg-del-msg');
                    const madgInput = document.querySelector('input[name="madg"]');
                    if (dgConfirmBtn && madgInput) {
                        dgConfirmBtn.onclick = function() {
                            dgDelMsg.textContent = 'Đang xử lý...';
                            dgDelMsg.className = 'dg-del-msg';
                            fetch('http://localhost:86/cnpm-be/api/danhgia/' + encodeURIComponent(madgInput.value), {
                                method: 'DELETE',
                                headers: { 'Content-Type': 'application/json' }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.message) {
                                    dgDelMsg.textContent = data.message;
                                    dgDelMsg.classList.add('success');
                                    setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchDanhGia === 'function') fetchDanhGia(); }, 1000);
                                } else {
                                    dgDelMsg.textContent = data.error || 'Xoá thất bại!';
                                    dgDelMsg.classList.add('error');
                                }
                            })
                            .catch(() => {
                                dgDelMsg.textContent = 'Lỗi kết nối máy chủ!';
                                dgDelMsg.classList.add('error');
                            });
                        };
                    }
                }, 0);
            }
        });
} 