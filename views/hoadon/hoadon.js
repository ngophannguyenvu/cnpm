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
        fetch('http://localhost:81/cnpm/api/hoaDonVaThanhToan', {
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

// Hàm helper để chuyển đổi mã trạng thái thành tên trạng thái
function getTrangThaiName(maTrangThai) {
    switch(maTrangThai) {
        case '1': return 'Đã thanh toán';
        case '0': return 'Chưa thanh toán';
        case '3': return 'Chờ thanh toán';
        case '4': return 'Đang chờ';
        default: return maTrangThai || 'Không xác định';
    }
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
            <td>${getTrangThaiName(item.Matrangthai)}</td>
            <td>
                <button class="hd-btn hd-detail" data-mahd="${item.MaHD}">Chi tiết</button>
                <button class="hd-btn hd-edit" data-mahd="${item.MaHD}">Sửa</button>
                <button class="hd-btn hd-delete" data-mahd="${item.MaHD}">Xoá</button>
            </td>
        </tr>
    `).join('');
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
                document.getElementById('hd-old-trangthai').textContent = getTrangThaiName(hd.Matrangthai);
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
        fetch('http://localhost:81/cnpm/api/hoadonvathanhtoan/' + form.mahd.value, {
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

window.hdTbody = document.getElementById('hd-tbody');
window.hdContent = document.getElementById('hd-content');
window.hdTableWrap = document.getElementById('hd-table-wrap'); 