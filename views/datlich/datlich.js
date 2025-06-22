// Quản lý Đặt lịch - JS
function fetchDatLichList() {
    const tbody = document.getElementById('dl-tbody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="5">Đang tải dữ liệu...</td></tr>';
    fetch('http://localhost:86/cnpm-be/api/datlich')
        .then(res => res.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                tbody.innerHTML = data.map(item => `
                    <tr>
                        <td>${item.MaDL || ''}</td>
                        <td>${item.Manguoidung || ''}</td>
                        <td>${item.Thoigiandatlich || ''}</td>
                        <td>${item.Trangthai_ || ''}</td>
                        <td>
                            <a href="edit.php?madl=${encodeURIComponent(item.MaDL || '')}">Sửa</a>
                            <a href="delete.php?madl=${encodeURIComponent(item.MaDL || '')}">Xóa</a>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu đặt lịch!</td></tr>';
            }
        })
        .catch(() => {
            tbody.innerHTML = '<tr><td colspan="5">Lỗi tải dữ liệu!</td></tr>';
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
        fetch('http://localhost:86/cnpm-be/api/datlich', {
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