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