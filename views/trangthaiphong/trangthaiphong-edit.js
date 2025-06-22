(function() {
    console.log('edit trangthaiphong loaded', document.getElementById('ttp-edit-form'), typeof _ttpData, _ttpData);
    const ttpEditForm = document.getElementById('ttp-edit-form');
    if (!ttpEditForm) return;
    const ttpEditMsg = document.getElementById('ttp-edit-msg');
    const ttpOldInfo = document.getElementById('ttp-old-info');
    // Hiển thị thông tin cũ nếu có
    const mattp = ttpEditForm.mattp.value;
    if (typeof _ttpData !== 'undefined' && mattp) {
        const ttp = (_ttpData || []).find(x => x.MatrangthaiP == mattp);
        if (ttp) {
            document.getElementById('ttp-old-mattp').textContent = ttp.MatrangthaiP || '';
            document.getElementById('ttp-old-tenttp').textContent = ttp.Tentrangthai || '';
            ttpEditForm.tenttp.value = ttp.Tentrangthai || '';
            ttpOldInfo.style.display = '';
        }
    }
    // Nút quay lại
    ttpEditForm.querySelector('.ttp-back').onclick = function() {
        if (typeof backToMain === 'function') backToMain();
    };
    // Submit form
    ttpEditForm.addEventListener('submit', e => { console.log('submit event'); });
    ttpEditForm.onsubmit = function(e) {
        e.preventDefault();
        console.log('Submit event triggered');
        ttpEditMsg.textContent = 'Đang xử lý...';
        ttpEditMsg.className = 'ttp-msg';
        const mattp = ttpEditForm.mattp.value; // lấy lại giá trị mới nhất
        console.log('submit event', mattp, ttpEditForm.tenttp.value);
        fetch('http://localhost:86/cnpm-be/api/trangthaiphong/' + mattp, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Tentrangthai: ttpEditForm.tenttp.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success' || data.message) {
                ttpEditMsg.textContent = data.message || 'Cập nhật trạng thái phòng thành công!';
                ttpEditMsg.className = 'ttp-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchTrangThaiPhong === 'function') fetchTrangThaiPhong(); }, 1000);
            } else {
                ttpEditMsg.textContent = data.message || 'Cập nhật thất bại!';
                ttpEditMsg.className = 'ttp-msg error';
            }
        })
        .catch(() => {
            ttpEditMsg.textContent = 'Lỗi kết nối máy chủ!';
            ttpEditMsg.className = 'ttp-msg error';
        });
    };
})(); 