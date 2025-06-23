// Quản lý User - JS
function fetchUserList() {
    const msg = document.getElementById('user-list-msg');
    const loading = document.getElementById('user-list-loading');
    const table = document.getElementById('user-list-table');
    const tbody = document.getElementById('user-list-tbody');
    loading.style.display = '';
    table.style.display = 'none';
    msg.textContent = '';
    fetch('http://localhost:81/cnpm/api/user')
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
                        <button class="user-action-btn" onclick="viewDetail('${user.Manguoidung}')">Chi tiết</button>
                        <button class="user-action-btn" onclick="editUser('${user.Manguoidung}')">Sửa</button>
                        <button class="user-action-btn" onclick="deleteUser('${user.Manguoidung}')">Xoá</button>
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
        fetch('http://localhost:81/cnpm/api/user', {
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
        fetch('http://localhost:81/cnpm/api/updateUser', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                manguoidung: form.manguoidung.value,
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

fetchUserList(); 