<style>
.user-list-box {
    max-width: 900px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.user-list-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.7rem;
    font-weight: bold;
}
.user-list-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 18px;
    overflow: hidden;
}
.user-list-table th, .user-list-table td {
    padding: 10px 12px;
    text-align: left;
}
.user-list-table th {
    background: #ff80ab;
    color: #fff;
    font-weight: 600;
}
.user-list-table tr:nth-child(even) {
    background: #ffe4ec;
}
.user-list-table td .user-action-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 4px 10px;
    margin: 0 2px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: background 0.2s;
}
.user-list-table td .user-action-btn:hover {
    background: #e73370;
}
.user-list-btns {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-bottom: 10px;
}
.user-btn {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 18px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
}
.user-btn:hover {
    background: #e73370;
}
.user-btn.user-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
}
.user-btn.user-back:hover {
    background: #ffe4ec;
}
.user-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.user-msg.success { color: #43a047; }
.user-msg.error { color: #e53935; }
@media (max-width: 700px) {
    .user-list-box { padding: 10px; }
    .user-list-title { font-size: 1.1rem; }
    .user-list-table th, .user-list-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="user-list-box">
    <div class="user-list-title">Danh sách người dùng</div>
    <div class="user-msg" id="user-list-msg"></div>
    <div class="user-list-btns">
        <button class="user-btn user-add">Thêm mới</button>
        <button class="user-btn user-back">Quay lại</button>
    </div>
    <div id="user-list-loading">Đang tải dữ liệu...</div>
    <table class="user-list-table" id="user-list-table" style="display:none">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Họ tên</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="user-list-tbody"></tbody>
    </table>
</div>
<script>
let _userData = window._userData || null;
let _userLoaded = window._userLoaded || false;
const userListMsg = document.getElementById('user-list-msg');
const userListLoading = document.getElementById('user-list-loading');
const userListTable = document.getElementById('user-list-table');
const userListTbody = document.getElementById('user-list-tbody');
function renderUserTable() {
    userListTbody.innerHTML = '';
    if (!_userData || !_userData.length) {
        userListTbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#ff4081">Không có dữ liệu</td></tr>';
        return;
    }
    _userData.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
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
        `;
        userListTbody.appendChild(tr);
    });
}
function fetchUserList() {
    userListLoading.style.display = '';
    userListTable.style.display = 'none';
    userListMsg.textContent = '';
    fetch('http://localhost:86/cnpm-BE/api/user')
        .then(res => res.json())
        .then(data => {
            _userData = Array.isArray(data) ? data : (data.data || []);
            window._userData = _userData;
            _userLoaded = true;
            window._userLoaded = true;
            renderUserTable();
            userListLoading.style.display = 'none';
            userListTable.style.display = '';
        })
        .catch(() => {
            userListMsg.textContent = 'Lỗi tải dữ liệu!';
            userListMsg.className = 'user-msg error';
            userListLoading.style.display = 'none';
        });
}
if (!_userLoaded) {
    fetchUserList();
} else {
    userListLoading.style.display = 'none';
    userListTable.style.display = '';
    renderUserTable();
}
function viewDetail(id) {
    if (typeof loadView === 'function') loadView('user/detail', { manguoidung: id });
}
function editUser(id) {
    if (typeof loadView === 'function') loadView('user/edit', { manguoidung: id });
}
function deleteUser(id) {
    if (typeof loadView === 'function') loadView('user/delete', { manguoidung: id });
}
document.querySelector('.user-add').onclick = function() {
    if (typeof loadView === 'function') loadView('user/add');
};
document.querySelector('.user-back').onclick = function() {
    if (typeof backToMain === 'function') backToMain();
};
</script> 