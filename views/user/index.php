<style>
.user-list-box {
    max-width: 1100px;
    margin: 38px auto 0 auto;
    background: linear-gradient(120deg, #fff0f6 0%, #fce1ee 100%);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(232,67,147,0.10);
    padding: 38px 30px 30px 30px;
    font-family: 'Poppins', Arial, sans-serif;
    animation: fadeInUp 0.7s;
}
.user-list-title {
    color: #e84393;
    text-align: left;
    margin-bottom: 28px;
    font-size: 2.1rem;
    font-weight: 700;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.user-list-title i {
    color: #e84393;
    font-size: 2.2rem;
    filter: drop-shadow(0 2px 8px #f8bbd0);
}
.user-list-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    border-radius: 14px;
    margin-bottom: 22px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(232,67,147,0.07);
}
.user-list-table th, .user-list-table td {
    padding: 14px 14px;
    text-align: left;
    font-size: 1.05rem;
}
.user-list-table th {
    background: #e84393;
    color: #fff;
    font-weight: 600;
    letter-spacing: 0.5px;
    border: none;
}
.user-list-table tr:nth-child(even) {
    background: #fff0f6;
}
.user-list-table tr {
    transition: background 0.18s;
}
.user-list-table tr:hover {
    background: #fce1ee;
}
.user-list-table td .user-action-btn {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 6px 16px;
    margin: 0 2px;
    font-size: 1rem;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.18s, transform 0.18s;
    box-shadow: 0 2px 8px rgba(232,67,147,0.08);
}
.user-list-table td .user-action-btn:hover {
    background: #e73370;
    transform: translateY(-2px) scale(1.05);
}
.user-list-btns {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-bottom: 18px;
}
.user-btn {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 26px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.18s, transform 0.18s;
    box-shadow: 0 2px 8px rgba(232,67,147,0.08);
    display: flex;
    align-items: center;
    gap: 8px;
}
.user-btn:hover {
    background: #e73370;
    transform: translateY(-2px) scale(1.04);
}
.user-btn.user-back {
    background: #fff;
    color: #e84393;
    border: 1.5px solid #ff80ab;
    box-shadow: none;
}
.user-btn.user-back:hover {
    background: #ffe4ec;
}
.user-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1.08rem;
}
.user-msg.success { color: #43a047; }
.user-msg.error { color: #e53935; }
@media (max-width: 900px) {
    .user-list-box { padding: 10px; }
    .user-list-title { font-size: 1.2rem; }
    .user-list-table th, .user-list-table td { padding: 8px 4px; font-size: 0.98rem; }
    .user-btn { font-size: 1rem; padding: 8px 10px; }
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<div class="user-list-box">
    <div class="user-list-title"><i class="fas fa-users"></i> Danh sách người dùng</div>
    <div class="user-msg" id="user-list-msg"></div>
    <div class="user-list-btns">
        <button class="user-btn user-add"><i class="fas fa-user-plus"></i> Thêm mới</button>
        <button class="user-btn user-back"><i class="fas fa-arrow-left"></i> Quay lại</button>
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
</script> 