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
    <div id="user-table-wrap">
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
</div>
<div id="user-content"></div>
<script src="views/user/user.js"></script>
<script>
function loadUserView(view, manguoidung = '') {
    fetch(`views/user/${view}.php`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('user-content').innerHTML = html;
            document.getElementById('user-table-wrap').style.display = 'none';
            if (view === 'add' && typeof initAddUserForm === 'function') {
                initAddUserForm();
            }
            if (view === 'edit' && typeof window.initEditUserForm === 'function') {
                window.initEditUserForm();
            }
            if (view !== 'add' && manguoidung) {
                document.querySelectorAll('[name="manguoidung"]').forEach(e => e.value = manguoidung);
            }
        });
}

document.querySelector('.user-add').onclick = function() {
    loadUserView('add');
};

document.getElementById('user-list-tbody').addEventListener('click', function(e) {
    if (e.target.classList.contains('user-detail')) {
        loadUserView('detail', e.target.dataset.manguoidung);
    } else if (e.target.classList.contains('user-edit')) {
        loadUserView('edit', e.target.dataset.manguoidung);
    } else if (e.target.classList.contains('user-delete')) {
        loadUserView('delete', e.target.dataset.manguoidung);
    }
});
</script> 