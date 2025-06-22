// Quản lý Trạng thái - JS
function fetchTrangThai() {
    const ttTbody = document.getElementById('tt-tbody');
    if (ttTbody) ttTbody.innerHTML = '<tr><td colspan="3">Đang tải dữ liệu...</td></tr>';
    fetch('http://localhost:86/cnpm-be/api/trangthai')
        .then(res => res.json())
        .then(data => {
            const arr = Array.isArray(data) ? data : (data.data || []);
            if (ttTbody) ttTbody.innerHTML = arr.length ? arr.map(item => `
                <tr>
                    <td>${item.Matrangthai ?? ''}</td>
                    <td>${item.Tentrangthai ?? ''}</td>
                    <td>
                        <button class="tt-btn tt-detail" data-matt="${item.Matrangthai}">Chi tiết</button>
                        <button class="tt-btn tt-edit" data-matt="${item.Matrangthai}">Sửa</button>
                        <button class="tt-btn tt-delete" data-matt="${item.Matrangthai}">Xoá</button>
                    </td>
                </tr>
            `).join('') : '<tr><td colspan="3">Không có dữ liệu</td></tr>';
        })
        .catch(() => {
            if (ttTbody) ttTbody.innerHTML = '<tr><td colspan="3">Lỗi tải dữ liệu</td></tr>';
        });
}
window.fetchTrangThai = fetchTrangThai;
fetchTrangThai();

function initAddTrangThaiForm() {
    const form = document.getElementById('tt-add-form');
    const msg = document.getElementById('tt-add-msg');
    if (!form) return;
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'tt-msg';
        fetch('http://localhost:86/cnpm-be/api/trangthai', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Matrangthai: form.matt.value,
                Tentrangthai: form.tentt.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success' || data.message) {
                msg.textContent = data.message || 'Thêm trạng thái thành công!';
                msg.className = 'tt-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); fetchTrangThai(); }, 1000);
            } else {
                msg.textContent = data.message || 'Thêm trạng thái thất bại!';
                msg.className = 'tt-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'tt-msg error';
        });
    };
}
window.initAddTrangThaiForm = initAddTrangThaiForm; 