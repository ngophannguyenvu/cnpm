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
window.initAddTrangThaiPhongForm = initAddTrangThaiPhongForm; 