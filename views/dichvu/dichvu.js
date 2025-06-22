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
window.initEditDichVuForm = initEditDichVuForm; 