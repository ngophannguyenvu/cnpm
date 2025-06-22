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
}

const hdTbody = document.getElementById('hd-tbody'); 