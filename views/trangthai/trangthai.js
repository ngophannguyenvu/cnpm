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

function loadTTView(view, matt = '') {
    fetch(`views/trangthai/${view}.php`)
        .then(res => res.text())
        .then(html => {
            ttContent.innerHTML = html;
            ttTableWrap.style.display = 'none';
            ttContent.scrollIntoView({behavior: 'smooth'});
            if (view === 'add' && typeof initAddTrangThaiForm === 'function') {
                initAddTrangThaiForm();
            }
            if (view !== 'add' && matt) {
                document.querySelectorAll('[name="matt"]').forEach(e => e.value = matt);
                if (view === 'detail') {
                    document.getElementById('tt-matt').textContent = matt;
                }
            }
            if (view === 'delete' && matt) {
                document.querySelectorAll('[name="matt"]').forEach(e => e.value = matt);
                // Gắn lại sự kiện cho nút Xoá và Quay lại
                const ttBackBtn = document.querySelector('.tt-back');
                if (ttBackBtn) {
                    ttBackBtn.onclick = function() {
                        if (typeof backToMain === 'function') backToMain();
                    };
                }
                const ttConfirmBtn = document.querySelector('.tt-confirm');
                const ttDelMsg = document.getElementById('tt-del-msg');
                const mattInput = document.querySelector('input[name="matt"]');
                if (ttConfirmBtn && mattInput) {
                    ttConfirmBtn.onclick = function() {
                        ttDelMsg.textContent = 'Đang xử lý...';
                        ttDelMsg.className = 'tt-msg';
                        fetch('http://localhost:86/cnpm-be/api/trangthai/' + mattInput.value, {
                            method: 'DELETE'
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success || data.status === 'success' || data.message) {
                                ttDelMsg.textContent = data.message || 'Xoá trạng thái thành công!';
                                ttDelMsg.className = 'tt-msg success';
                                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); }, 1000);
                            } else {
                                ttDelMsg.textContent = data.message || 'Xoá thất bại!';
                                ttDelMsg.className = 'tt-msg error';
                            }
                        })
                        .catch(() => {
                            ttDelMsg.textContent = 'Lỗi kết nối máy chủ!';
                            ttDelMsg.className = 'tt-msg error';
                        });
                    };
                }
            }
            // Thêm đoạn này để re-execute script inline
            ttContent.querySelectorAll('script').forEach(oldScript => {
                const newScript = document.createElement('script');
                if (oldScript.src) newScript.src = oldScript.src;
                else newScript.textContent = oldScript.textContent;
                document.body.appendChild(newScript).parentNode.removeChild(newScript);
            });
        });
}

// Đã xử lý sự kiện xóa trong loadTTView, không cần đoạn này nữa 