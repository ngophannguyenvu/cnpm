// Quản lý Đặt lịch - JS
function fetchDatLichList() {
    const tbody = document.getElementById('dl-tbody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"></div></td></tr>';
    
    fetch('http://localhost:81/cnpm/api/datlich')
        .then(res => res.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                tbody.innerHTML = data.map(item => {
                    const trangthai = (item.Trangthai_ && item.Trangthai_.trim()) ? item.Trangthai_.trim() : 'Đang chờ';
                    
                    // Tạo các nút thao tác cơ bản (luôn hiển thị)
                    let actionBtns = `
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-warning btn-sm" onclick="window.location.href='edit.php?madl=${encodeURIComponent(item.MaDL || '')}'">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="if(confirm('Bạn có chắc muốn xóa?')) window.location.href='delete.php?madl=${encodeURIComponent(item.MaDL || '')}'">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                    `;

                    // Thêm nút xác nhận nếu trạng thái là "Đang chờ"
                    if (trangthai.toLowerCase() === 'đang chờ') {
                        actionBtns += `
                            <button class="btn btn-success btn-sm dl-btn-confirm" data-madl="${item.MaDL}">
                                <i class="fas fa-check"></i> Xác nhận lịch đặt
                            </button>
                        `;
                    }
                    // Thêm nút lập hóa đơn nếu trạng thái là "Xác nhận", "Chờ thanh toán", hoặc "Chưa thanh toán" (chưa lập hóa đơn)
                    else if (trangthai.toLowerCase() === 'xác nhận' || 
                             trangthai.toLowerCase() === 'chờ thanh toán' || 
                             trangthai.toLowerCase() === 'chưa thanh toán') {
                        actionBtns += `
                            <button class="btn btn-primary btn-sm dl-btn-invoice" data-madl="${item.MaDL}">
                                <i class="fas fa-file-invoice"></i> Lập hóa đơn
                            </button>
                        `;
                    }
                    // Hiển thị thông báo đã lập hóa đơn nếu trạng thái là "Đã thanh toán"
                    else if (trangthai.toLowerCase() === 'đã thanh toán') {
                        actionBtns += `
                            <span class="badge bg-success">Đã lập hóa đơn</span>
                        `;
                    }

                    actionBtns += '</div>';

                    // Tạo badge cho trạng thái
                    const getBadgeClass = (status) => {
                        switch(status.toLowerCase()) {
                            case 'đang chờ': return 'bg-warning text-dark';
                            case 'xác nhận': return 'bg-success';
                            case 'đã hoàn thành': return 'bg-info';
                            case 'đã hủy': return 'bg-danger';
                            case 'đã thanh toán': return 'bg-primary';
                            case 'chờ thanh toán': return 'bg-info';
                            case 'chưa thanh toán': return 'bg-secondary';
                            default: return 'bg-secondary';
                        }
                    };

                    return `
                        <tr data-madl="${item.MaDL}">
                            <td>${item.MaDL || ''}</td>
                            <td>${item.Manguoidung || ''}</td>
                            <td>${formatDateTime(item.Thoigiandatlich) || ''}</td>
                            <td><span class="badge ${getBadgeClass(trangthai)}">${trangthai}</span></td>
                            <td>${actionBtns}</td>
                        </tr>
                    `;
                }).join('');

                // Gán sự kiện cho nút xác nhận - không cần hỏi xác nhận
                document.querySelectorAll('.dl-btn-confirm').forEach(btn => {
                    btn.onclick = function() {
                        const madl = this.getAttribute('data-madl');
                        updateTrangThai(madl, 'Xác nhận');
                    };
                });

                // Gán sự kiện cho nút lập hóa đơn
                document.querySelectorAll('.dl-btn-invoice').forEach(btn => {
                    btn.onclick = function() {
                        const madl = this.getAttribute('data-madl');
                        window.location.href = "/cnpm/views/hoadon/add.php?madl=" + madl;
                    };
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Không có dữ liệu đặt lịch!</td></tr>';
            }
        })
        .catch(() => {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Lỗi tải dữ liệu!</td></tr>';
        });
}

// Hàm format ngày giờ
function formatDateTime(dateTimeStr) {
    if (!dateTimeStr) return '';
    const dt = new Date(dateTimeStr);
    if (isNaN(dt.getTime())) return dateTimeStr;
    return dt.toLocaleString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Hàm cập nhật trạng thái - không hiện thông báo xác nhận
function updateTrangThai(madl, trangthai) {
    const row = document.querySelector(`tr[data-madl="${madl}"]`);
    if (!row) return;

    const manguoidung = row.querySelector('td:nth-child(2)').textContent;
    const thoigiandatlich = row.querySelector('td:nth-child(3)').textContent;

    fetch(`http://localhost:81/cnpm/api/datlich/${madl}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            Trangthai: trangthai,
            Manguoidung: manguoidung,
            Thoigiandatlich: thoigiandatlich
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            // Cập nhật lại bảng ngay lập tức
            fetchDatLichList();
        } else {
            console.error('Cập nhật trạng thái thất bại:', data.error);
        }
    })
    .catch(error => {
        console.error('Lỗi kết nối:', error);
    });
}

window.fetchDatLichList = fetchDatLichList;

fetchDatLichList();

function initAddDatLichForm() {
    const form = document.getElementById('dl-add-form');
    const msg = document.getElementById('dl-add-msg');
    if (!form) return;
    form.onsubmit = function(e) {
        e.preventDefault();
        const manguoidung = this.manguoidung.value.trim();
        const thoigiandatlichInput = this.thoigiandatlich.value;
        const trangthai = this.trangthai.value.trim();
        msg.textContent = '';
        msg.className = 'dl-form-msg';
        if (!manguoidung || !thoigiandatlichInput || !trangthai) {
            msg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
            msg.classList.add('error');
            return;
        }
        let thoigiandatlich;
        try {
            thoigiandatlich = new Date(thoigiandatlichInput).toISOString().slice(0, 19).replace('T', ' ');
        } catch (error) {
            msg.textContent = 'Định dạng thời gian không hợp lệ!';
            msg.classList.add('error');
            return;
        }
        fetch('http://localhost:81/cnpm/api/datlich', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Manguoidung: manguoidung,
                Thoigiandatlich: thoigiandatlich,
                Trangthai: trangthai
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                msg.textContent = data.message;
                msg.classList.add('success');
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); fetchDatLichList(); }, 1000);
            } else {
                msg.textContent = data.error || (data.errors ? Object.values(data.errors).join(', ') : 'Thêm thất bại!');
                msg.classList.add('error');
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ hoặc vấn đề CORS!';
            msg.classList.add('error');
        });
    };
}
window.initAddDatLichForm = initAddDatLichForm;

window.backToMain = function() {
    const content = document.getElementById('dl-content');
    const tableWrap = document.getElementById('dl-table-wrap');
    if (content) content.innerHTML = '';
    if (tableWrap) tableWrap.style.display = '';
    fetchDatLichList();
};

// Sự kiện nút Thêm mới
const btnAdd = document.getElementById('dl-btn-add');
if (btnAdd) {
    btnAdd.onclick = function() {
        fetch('views/datlich/add.php')
            .then(res => res.text())
            .then(html => {
                const content = document.getElementById('dl-content');
                const tableWrap = document.getElementById('dl-table-wrap');
                if (content) content.innerHTML = html;
                if (tableWrap) tableWrap.style.display = 'none';
                if (typeof initAddDatLichForm === 'function') initAddDatLichForm();
            });
    };
}

function initEditDatLichForm() {
    const form = document.getElementById('dl-edit-form');
    const msg = document.getElementById('dl-edit-msg');
    if (!form) return;
    const madl = form.madl.value;
    if (typeof _dlData !== 'undefined' && madl) {
        const dl = (_dlData || []).find(x => x.MaDL == madl);
        if (dl) {
            form.madl.value = dl.MaDL || '';
            form.manguoidung.value = dl.Manguoidung || '';
            // Chuyển đổi thời gian về dạng datetime-local
            if (dl.Thoigiandatlich) {
                let dt = dl.Thoigiandatlich.replace(' ', 'T');
                if (dt.length === 16) dt += ':00';
                form.thoigiandatlich.value = dt;
            } else {
                form.thoigiandatlich.value = '';
            }
            form.trangthai.value = dl.Trangthai_ || '';
            // Hiển thị thông tin cũ
            const oldInfo = document.getElementById('dl-old-info');
            if (oldInfo) {
                oldInfo.style.display = '';
                document.getElementById('dl-old-madl').textContent = dl.MaDL || '';
                document.getElementById('dl-old-manguoidung').textContent = dl.Manguoidung || '';
                document.getElementById('dl-old-thoigiandatlich').textContent = dl.Thoigiandatlich || '';
                document.getElementById('dl-old-trangthai').textContent = dl.Trangthai_ || '';
            }
        }
    }
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = '';
        msg.className = 'dl-form-msg';
        const manguoidung = form.manguoidung.value.trim();
        const thoigiandatlichInput = form.thoigiandatlich.value;
        const trangthai = form.trangthai.value.trim();
        if (!manguoidung || !thoigiandatlichInput || !trangthai) {
            msg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
            msg.classList.add('error');
            return;
        }
        let thoigiandatlich;
        try {
            thoigiandatlich = new Date(thoigiandatlichInput).toISOString().slice(0, 19).replace('T', ' ');
        } catch (error) {
            msg.textContent = 'Định dạng thời gian không hợp lệ!';
            msg.classList.add('error');
            return;
        }
        fetch('http://localhost:81/cnpm/api/datlich/' + form.madl.value, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Manguoidung: manguoidung,
                Thoigiandatlich: thoigiandatlich,
                Trangthai: trangthai
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                msg.textContent = data.message;
                msg.classList.add('success');
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); fetchDatLichList(); }, 1000);
            } else {
                msg.textContent = data.error || (data.errors ? Object.values(data.errors).join(', ') : 'Cập nhật thất bại!');
                msg.classList.add('error');
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ hoặc vấn đề CORS!';
            msg.classList.add('error');
        });
    };
}
window.initEditDatLichForm = initEditDatLichForm; 