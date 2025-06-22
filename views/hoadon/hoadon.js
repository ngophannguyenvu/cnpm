function initAddHoaDonForm() {
    console.log('initAddHoaDonForm called');
    const hdAddForm = document.getElementById('hd-add-form');
    const hdAddMsg = document.getElementById('hd-add-msg');
    if (!hdAddForm) {
        console.error('Không tìm thấy form hd-add-form');
        return;
    }
    // Xoá sự kiện cũ nếu có
    hdAddForm.onsubmit = null;
    hdAddForm.onsubmit = function(e) {
        e.preventDefault();
        hdAddMsg.textContent = 'Đang xử lý...';
        hdAddMsg.className = 'hd-msg';
        fetch('http://localhost:86/cnpm-be/api/hoaDonVaThanhToan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                NgayThanhToan: hdAddForm.ngay.value + ' 00:00:00',
                Tongtien: hdAddForm.tongtien.value + '',
                MaDL: hdAddForm.madl.value + '',
                Manguoidung: hdAddForm.manguoidung.value + '',
                Maphong: hdAddForm.maphong.value + '',
                MaPT: hdAddForm.mapt.value + '',
                Matrangthai: hdAddForm.trangthai.value + ''
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message === 'Hóa đơn được thêm thành công') {
                hdAddMsg.textContent = 'Thêm hóa đơn thành công!';
                hdAddMsg.className = 'hd-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
            } else {
                hdAddMsg.textContent = data.error || 'Thêm hóa đơn thất bại!';
                hdAddMsg.className = 'hd-msg error';
            }
        })
        .catch(() => {
            hdAddMsg.textContent = 'Lỗi kết nối máy chủ!';
            hdAddMsg.className = 'hd-msg error';
        });
    };
    // Nút quay lại
    const backBtn = document.querySelector('.hd-back');
    if (backBtn) {
        backBtn.onclick = function() {
            if (typeof backToMain === 'function') backToMain();
        };
    }
}

function backToMain() {
    hdContent.innerHTML = '';
    hdTableWrap.style.display = '';
    fetchHoaDon(); // Luôn gọi lại API để lấy dữ liệu mới nhất
}

function renderRows(data) {
    console.log('renderRows data:', data);
    if (!Array.isArray(data) || data.length === 0) {
        hdTbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu</td></tr>';
        return;
    }
    hdTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.MaHD}</td>
            <td>${item.NgayThanhToan || ''}</td>
            <td>${item.Tongtien ? Number(item.Tongtien).toLocaleString('vi-VN') + 'đ' : ''}</td>
            <td>${item.Matrangthai || ''}</td>
            <td>
                <button class="hd-btn hd-detail" data-mahd="${item.MaHD}">Chi tiết</button>
                <button class="hd-btn hd-edit" data-mahd="${item.MaHD}">Sửa</button>
                <button class="hd-btn hd-delete" data-mahd="${item.MaHD}">Xoá</button>
            </td>
        </tr>
    `).join('');
    addHoaDonDeleteListeners();
}

function initEditHoaDonForm() {
    const form = document.getElementById('hd-edit-form');
    const msg = document.getElementById('hd-edit-msg');
    if (!form) return;
    const mahd = form.mahd.value;
    if (typeof _hdData !== 'undefined' && mahd) {
        const hd = (_hdData || []).find(x => x.MaHD == mahd);
        if (hd) {
            form.ngay.value = hd.NgayThanhToan || '';
            form.tongtien.value = hd.Tongtien || '';
            form.trangthai.value = hd.Matrangthai || '';
            form.madl.value = hd.MaDL || '';
            form.manguoidung.value = hd.Manguoidung || '';
            form.maphong.value = hd.Maphong || '';
            form.mapt.value = hd.MaPT || '';
            // Hiển thị thông tin cũ
            const oldInfo = document.getElementById('hd-old-info');
            if (oldInfo) {
                oldInfo.style.display = '';
                document.getElementById('hd-old-mahd').textContent = hd.MaHD || '';
                document.getElementById('hd-old-ngay').textContent = hd.NgayThanhToan || '';
                document.getElementById('hd-old-tongtien').textContent = hd.Tongtien || '';
                document.getElementById('hd-old-trangthai').textContent = hd.Matrangthai == '1' ? 'Đã thanh toán' : (hd.Matrangthai == '0' ? 'Chưa thanh toán' : '');
                document.getElementById('hd-old-madl').textContent = hd.MaDL || '';
                document.getElementById('hd-old-manguoidung').textContent = hd.Manguoidung || '';
                document.getElementById('hd-old-maphong').textContent = hd.Maphong || '';
                document.getElementById('hd-old-mapt').textContent = hd.MaPT || '';
            }
        }
    }
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'hd-msg';
        fetch('http://localhost:86/cnpm-be/api/hoadonvathanhtoan/' + form.mahd.value, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                NgayThanhToan: form.ngay.value,
                Tongtien: form.tongtien.value,
                Matrangthai: form.trangthai.value,
                MaDL: form.madl.value,
                Manguoidung: form.manguoidung.value,
                Maphong: form.maphong.value,
                MaPT: form.mapt.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success') {
                msg.textContent = 'Cập nhật hóa đơn thành công!';
                msg.className = 'hd-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchHoaDon === 'function') fetchHoaDon(); }, 1000);
            } else {
                msg.textContent = data.message || 'Cập nhật thất bại!';
                msg.className = 'hd-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'hd-msg error';
        });
    };
}
window.initEditHoaDonForm = initEditHoaDonForm;

function addHoaDonDeleteListeners() {
    document.querySelectorAll('.hd-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-mahd');
            if (confirm('Bạn có chắc muốn xoá hóa đơn này?')) {
                fetch('http://localhost:86/cnpm-be/api/hoaDonVaThanhToan/' + id, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Đã xoá!');
                    fetchHoaDon();
                })
                .catch(() => alert('Lỗi kết nối máy chủ!'));
            }
        });
    });
}

window.hdTbody = document.getElementById('hd-tbody');
window.hdContent = document.getElementById('hd-content');
window.hdTableWrap = document.getElementById('hd-table-wrap');

function loadHDView(view, mahd = '') {
    fetch(`views/hoadon/${view}.php`)
        .then(res => res.text())
        .then(html => {
            hdContent.innerHTML = html;
            hdTableWrap.style.display = 'none';
            hdContent.scrollIntoView({behavior: 'smooth'});
            if (view === 'add') {
                if (typeof initAddHoaDonForm === 'function') initAddHoaDonForm();
            }
            if (view !== 'add' && mahd) {
                document.querySelectorAll('[name="mahd"]').forEach(e => e.value = mahd);
            }
            if (view === 'edit' && typeof window.initEditHoaDonForm === 'function') {
                window.initEditHoaDonForm();
            }
            if (view === 'delete' && mahd) {
                document.querySelectorAll('[name="mahd"]').forEach(e => e.value = mahd);
                // Gắn lại sự kiện cho nút Xoá và Quay lại
                const hdBackBtn = document.querySelector('.hd-back');
                if (hdBackBtn) {
                    hdBackBtn.onclick = function() {
                        if (typeof backToMain === 'function') backToMain();
                    };
                }
                const hdConfirmBtn = document.querySelector('.hd-confirm');
                const hdDelMsg = document.getElementById('hd-del-msg');
                const mahdInput = document.querySelector('input[name="mahd"]');
                if (hdConfirmBtn && mahdInput) {
                    hdConfirmBtn.onclick = function() {
                        hdDelMsg.textContent = 'Đang xử lý...';
                        hdDelMsg.className = 'hd-msg';
                        fetch('http://localhost:86/cnpm-be/api/hoaDonVaThanhToan/' + mahdInput.value, {
                            method: 'DELETE'
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success || data.status === 'success') {
                                hdDelMsg.textContent = data.message || 'Xoá hóa đơn thành công!';
                                hdDelMsg.className = 'hd-msg success';
                                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchHoaDon === 'function') fetchHoaDon(); }, 1000);
                            } else {
                                hdDelMsg.textContent = data.message || 'Xoá thất bại!';
                                hdDelMsg.className = 'hd-msg error';
                            }
                        })
                        .catch(() => {
                            hdDelMsg.textContent = 'Lỗi kết nối máy chủ!';
                            hdDelMsg.className = 'hd-msg error';
                        });
                    };
                }
            }
        });
} 