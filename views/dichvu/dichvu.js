function initAddDichVuForm() {
    const dvAddForm = document.getElementById('dv-add-form');
    const dvAddMsg = document.getElementById('dv-add-msg');
    if (!dvAddForm) return;
    dvAddForm.onsubmit = function(e) {
        e.preventDefault();
        dvAddMsg.textContent = 'Đang xử lý...';
        dvAddMsg.className = 'dv-msg';
        fetch('http://localhost:86/cnpm-be/api/dichvu', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Tendichvu: dvAddForm.tendichvu.value,
                Gia: dvAddForm.gia.value,
                MoTa: dvAddForm.mota.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message === 'Dich vu created successfully') {
                dvAddMsg.textContent = 'Thêm dịch vụ thành công!';
                dvAddMsg.className = 'dv-msg success';
                setTimeout(() => { if (typeof backToDVMain === 'function') backToDVMain(); }, 1000);
            } else {
                dvAddMsg.textContent = data.error || 'Thêm dịch vụ thất bại!';
                dvAddMsg.className = 'dv-msg error';
            }
        })
        .catch(() => {
            dvAddMsg.textContent = 'Lỗi kết nối máy chủ!';
            dvAddMsg.className = 'dv-msg error';
        });
    };
}

function initEditDichVuForm() {
    const dvEditForm = document.getElementById('dv-edit-form');
    const dvEditMsg = document.getElementById('dv-edit-msg');
    if (!dvEditForm) return;
    const madv = dvEditForm.madv.value;
    if (typeof _dvData !== 'undefined' && madv) {
        const dv = (_dvData || []).find(x => x.MaDV == madv);
        if (dv) {
            dvEditForm.tendichvu.value = dv.Tendichvu || '';
            dvEditForm.gia.value = dv.Gia || '';
            dvEditForm.mota.value = dv.MoTa || '';
            const oldInfo = document.getElementById('dv-old-info');
            if (oldInfo) {
                oldInfo.style.display = '';
                document.getElementById('dv-old-madv').textContent = dv.MaDV;
                document.getElementById('dv-old-tendichvu').textContent = dv.Tendichvu || '';
                document.getElementById('dv-old-gia').textContent = dv.Gia || '';
                document.getElementById('dv-old-mota').textContent = dv.MoTa || '';
            }
        }
    }
    dvEditForm.onsubmit = function(e) {
        e.preventDefault();
        dvEditMsg.textContent = 'Đang xử lý...';
        dvEditMsg.className = 'dv-msg';
        fetch('http://localhost:86/cnpm-be/api/dichvu/' + dvEditForm.madv.value, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Tendichvu: dvEditForm.tendichvu.value,
                Gia: dvEditForm.gia.value,
                MoTa: dvEditForm.mota.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message === 'Product updated successfully') {
                dvEditMsg.textContent = 'Cập nhật dịch vụ thành công!';
                dvEditMsg.className = 'dv-msg success';
                setTimeout(() => { if (typeof backToDVMain === 'function') backToDVMain(); }, 1000);
            } else {
                dvEditMsg.textContent = data.error || data.message || 'Cập nhật thất bại!';
                dvEditMsg.className = 'dv-msg error';
            }
        })
        .catch(() => {
            dvEditMsg.textContent = 'Lỗi kết nối máy chủ!';
            dvEditMsg.className = 'dv-msg error';
        });
    };
}

function addDichVuDeleteListeners() {
    document.querySelectorAll('.dv-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-madv');
            loadDVView('delete', id);
        });
    });
}

function renderDVRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        dvTbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu</td></tr>';
        return;
    }
    dvTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.MaDV}</td>
            <td>${item.Tendichvu || ''}</td>
            <td>${item.Gia ? Number(item.Gia).toLocaleString('vi-VN') + 'đ' : ''}</td>
            <td>${item.MoTa || ''}</td>
            <td>
                <button class="dv-btn dv-edit" data-madv="${item.MaDV}">Sửa</button>
                <button class="dv-btn dv-delete" data-madv="${item.MaDV}">Xoá</button>
            </td>
        </tr>
    `).join('');
    addDichVuDeleteListeners();
}

function loadDVView(view, madv = '') {
    fetch(`views/dichvu/${view}.php`)
        .then(res => res.text())
        .then(html => {
            dvContent.innerHTML = html;
            dvTableWrap.style.display = 'none';
            dvContent.scrollIntoView({behavior: 'smooth'});
            if (view === 'add' && typeof initAddDichVuForm === 'function') {
                initAddDichVuForm();
            }
            if (view === 'edit' && typeof window.initEditDichVuForm === 'function') {
                window.initEditDichVuForm();
            }
            if (view === 'delete' && madv) {
                document.querySelectorAll('[name="madv"]').forEach(e => e.value = madv);
                const dvBackBtn = document.querySelector('.dv-back');
                if (dvBackBtn) {
                    dvBackBtn.onclick = function() {
                        if (typeof backToDVMain === 'function') backToDVMain();
                    };
                }
                const dvConfirmBtn = document.querySelector('.dv-confirm');
                const dvDelMsg = document.getElementById('dv-del-msg');
                const madvInput = document.querySelector('input[name="madv"]');
                if (dvConfirmBtn && madvInput) {
                    dvConfirmBtn.onclick = function() {
                        dvDelMsg.textContent = 'Đang xử lý...';
                        dvDelMsg.className = 'dv-msg';
                        fetch('http://localhost:86/cnpm-be/api/dichvu/' + madvInput.value, {
                            method: 'DELETE'
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.message === 'Xóa dịch vụ thành công') {
                                dvDelMsg.textContent = 'Xoá dịch vụ thành công!';
                                dvDelMsg.className = 'dv-msg success';
                                setTimeout(() => { if (typeof backToDVMain === 'function') backToDVMain(); }, 1000);
                            } else {
                                dvDelMsg.textContent = data.message || 'Xoá thất bại!';
                                dvDelMsg.className = 'dv-msg error';
                            }
                        })
                        .catch(() => {
                            dvDelMsg.textContent = 'Lỗi kết nối máy chủ!';
                            dvDelMsg.className = 'dv-msg error';
                        });
                    };
                }
            }
            if (view !== 'add' && madv) {
                document.querySelectorAll('[name="madv"]').forEach(e => e.value = madv);
            }
        });
}

window.initEditDichVuForm = initEditDichVuForm; 