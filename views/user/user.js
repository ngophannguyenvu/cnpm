// Quản lý User - JS
function fetchUserList() {
    const msg = document.getElementById('user-list-msg');
    const loading = document.getElementById('user-list-loading');
    const table = document.getElementById('user-list-table');
    const tbody = document.getElementById('user-list-tbody');
    loading.style.display = '';
    table.style.display = 'none';
    msg.textContent = '';
    fetch('http://localhost:86/cnpm-be/api/user')
        .then(res => res.json())
        .then(data => {
            const arr = Array.isArray(data) ? data : (data.data || []);
            tbody.innerHTML = arr.length ? arr.map(user => `
                <tr>
                    <td>${user.Manguoidung || ''}</td>
                    <td>${user.Hoten || ''}</td>
                    <td>${user.SDT || ''}</td>
                    <td>${user.Email || ''}</td>
                    <td>${user.Gioitinh || ''}</td>
                    <td>${user.Ngaysinh || ''}</td>
                    <td>
                        <button class="user-action-btn user-detail" data-manguoidung="${user.Manguoidung}">Chi tiết</button>
                        <button class="user-action-btn user-edit" data-manguoidung="${user.Manguoidung}">Sửa</button>
                        <button class="user-action-btn user-delete" data-manguoidung="${user.Manguoidung}">Xoá</button>
                    </td>
                </tr>
            `).join('') : '<tr><td colspan="7" style="text-align:center;color:#ff4081">Không có dữ liệu</td></tr>';
            loading.style.display = 'none';
            table.style.display = '';
        })
        .catch(() => {
            msg.textContent = 'Lỗi tải dữ liệu!';
            msg.className = 'user-msg error';
            loading.style.display = 'none';
        });
}
window.fetchUserList = fetchUserList;

function initAddUserForm() {
    const form = document.getElementById('user-add-form');
    const msg = document.getElementById('user-add-msg');
    if (!form) return;
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'user-msg';
        fetch('http://localhost:86/cnpm-be/api/user', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                hoten: form.hoten.value,
                sdt: form.sdt.value,
                diachi: form.diachi.value,
                email: form.email.value,
                ngaysinh: form.ngaysinh.value,
                gioitinh: form.gioitinh.value,
                password: form.password.value,
                role: "user"
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success' || data.message) {
                msg.textContent = data.message || 'Thêm người dùng thành công!';
                msg.className = 'user-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); fetchUserList(); }, 1000);
            } else {
                msg.textContent = data.message || 'Thêm người dùng thất bại!';
                msg.className = 'user-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'user-msg error';
        });
    };
}
window.initAddUserForm = initAddUserForm;

function initEditUserForm() {
    const form = document.getElementById('user-edit-form');
    const msg = document.getElementById('user-edit-msg');
    if (!form) return;
    const manguoidung = form.manguoidung.value;
    if (typeof _userData !== 'undefined' && manguoidung) {
        const user = (_userData || []).find(x => x.Manguoidung == manguoidung);
        if (user) {
            form.hoten.value = user.Hoten || '';
            form.sdt.value = user.SDT || '';
            form.diachi.value = user.DiaChi || '';
            form.email.value = user.Email || '';
            form.ngaysinh.value = user.Ngaysinh || '';
            form.gioitinh.value = user.Gioitinh || '';
            // Hiển thị thông tin cũ
            const oldInfo = document.getElementById('user-old-info');
            if (oldInfo) {
                oldInfo.style.display = '';
                document.getElementById('user-old-manguoidung').textContent = user.Manguoidung || '';
                document.getElementById('user-old-hoten').textContent = user.Hoten || '';
                document.getElementById('user-old-sdt').textContent = user.SDT || '';
                document.getElementById('user-old-diachi').textContent = user.DiaChi || '';
                document.getElementById('user-old-email').textContent = user.Email || '';
                document.getElementById('user-old-ngaysinh').textContent = user.Ngaysinh || '';
                document.getElementById('user-old-gioitinh').textContent = user.Gioitinh || '';
            }
        }
    }
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'user-msg';
        fetch('http://localhost:86/cnpm-BE/api/user/' + form.manguoidung.value, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                hoten: form.hoten.value,
                sdt: form.sdt.value,
                diachi: form.diachi.value,
                email: form.email.value,
                ngaysinh: form.ngaysinh.value,
                gioitinh: form.gioitinh.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success' || data.message) {
                msg.textContent = data.message || 'Cập nhật người dùng thành công!';
                msg.className = 'user-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); fetchUserList(); }, 1000);
            } else {
                msg.textContent = data.message || 'Cập nhật thất bại!';
                msg.className = 'user-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'user-msg error';
        });
    };
}
window.initEditUserForm = initEditUserForm;

function loadUserView(view, manguoidung = '') {
    fetch(`views/user/${view}.php`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('user-content').innerHTML = html;
            document.getElementById('user-table-wrap').style.display = 'none';
            document.getElementById('user-content').scrollIntoView({behavior: 'smooth'});
            if (view === 'add' && typeof initAddUserForm === 'function') {
                initAddUserForm();
            }
            if (view === 'edit' && typeof window.initEditUserForm === 'function') {
                window.initEditUserForm();
            }
            if (view !== 'add' && manguoidung) {
                document.querySelectorAll('[name="manguoidung"]').forEach(e => e.value = manguoidung);
                if (view === 'detail') {
                    const el = document.getElementById('user-manguoidung');
                    if (el) el.textContent = manguoidung;
                }
            }
            if (view === 'delete' && manguoidung) {
                document.querySelectorAll('[name="manguoidung"]').forEach(e => e.value = manguoidung);
                // Gắn lại sự kiện cho nút Xoá và Quay lại
                const userBackBtn = document.querySelector('.user-back');
                if (userBackBtn) {
                    userBackBtn.onclick = function() {
                        if (typeof backToMain === 'function') backToMain();
                    };
                }
                const userConfirmBtn = document.querySelector('.user-confirm');
                const userDelMsg = document.getElementById('user-del-msg');
                const manguoidungInput = document.querySelector('input[name="manguoidung"]');
                if (userConfirmBtn && manguoidungInput) {
                    userConfirmBtn.onclick = function() {
                        userDelMsg.textContent = 'Đang xử lý...';
                        userDelMsg.className = 'user-msg';
                        fetch('http://localhost:86/cnpm-be/api/user/' + manguoidungInput.value, {
                            method: 'DELETE'
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success || data.status === 'success' || data.message) {
                                userDelMsg.textContent = data.message || 'Xoá người dùng thành công!';
                                userDelMsg.className = 'user-msg success';
                                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchUser === 'function') fetchUser(); }, 1000);
                            } else {
                                userDelMsg.textContent = data.message || 'Xoá thất bại!';
                                userDelMsg.className = 'user-msg error';
                            }
                        })
                        .catch(() => {
                            userDelMsg.textContent = 'Lỗi kết nối máy chủ!';
                            userDelMsg.className = 'user-msg error';
                        });
                    };
                }
            }
        });
}

fetchUserList();

// Event delegation cho các nút thao tác user
const userTbody = document.getElementById('user-list-tbody');
userTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('user-detail')) {
        loadUserView('detail', e.target.dataset.manguoidung);
    } else if (e.target.classList.contains('user-edit')) {
        loadUserView('edit', e.target.dataset.manguoidung);
    } else if (e.target.classList.contains('user-delete')) {
        loadUserView('delete', e.target.dataset.manguoidung);
    }
});

function backToMain() {
    document.getElementById('user-content').innerHTML = '';
    document.getElementById('user-table-wrap').style.display = '';
    fetchUserList();
}
window.backToMain = backToMain; 